package com.example.onlinespacefinder

import android.Manifest
import android.app.ProgressDialog
import android.content.Intent
import android.content.IntentSender
import android.content.pm.PackageManager
import android.location.Location
import android.os.Bundle
import android.os.Handler
import android.text.TextUtils
import android.util.Log
import android.view.Menu
import android.view.MenuItem
import android.view.View
import android.widget.AdapterView
import android.widget.ListView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.widget.SearchView
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.example.onlinespacefinder.ui.home.HomeFragment
import com.google.android.gms.common.ConnectionResult
import com.google.android.gms.common.api.GoogleApiClient
import com.google.android.gms.location.*
import com.google.android.gms.maps.GoogleMap
import com.google.android.gms.maps.OnMapReadyCallback
import org.json.JSONException
import org.json.JSONObject
import java.util.*

class SpaceActivity : AppCompatActivity(), OnMapReadyCallback, GoogleApiClient.ConnectionCallbacks,
    GoogleApiClient.OnConnectionFailedListener, LocationListener {
    private var userid: Int? = null
    private var sessionManager: SessionManager? = null
    private var mGoogleApiClient: GoogleApiClient? = null
    private var myLocation: Location? = null
    private var currentLatitude: Double? = null
    private var currentLongitude: Double? = null
    private var priceFrom: String? = null
    private var priceTo: String? = null
    private var type: String? = null
    private var category: String? = null
    private var spaceList: MutableList<Space>? = null
    private var adapter: SpaceAdapter? = null
    private var refreshLayout: SwipeRefreshLayout? = null
    private var list: ListView? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_space)
        setUPGClient()

        //toolbar
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_FULLSCREEN
        supportActionBar?.setDisplayHomeAsUpEnabled(true)

        //for the session values
        sessionManager = SessionManager(this)
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        userid = user[SessionManager.USER_ID]!!.toInt()

        val intent = intent
        priceFrom = intent.getStringExtra(HomeFragment.FROM)
        priceTo = intent.getStringExtra(HomeFragment.TO)
        type = intent.getStringExtra(HomeFragment.TYPE)
        category = intent.getStringExtra(HomeFragment.CATEGORY)

        list = findViewById(R.id.list)
        spaceList = ArrayList()
        adapter =
            SpaceAdapter(
                (spaceList as ArrayList<Space>)!!,
                this
            )
        refreshLayout = findViewById(R.id.swipe_refresh_layout)

        refreshLayout!!.setOnRefreshListener(SwipeRefreshLayout.OnRefreshListener {
            spaceList!!.clear()
            loadSpace(priceFrom!!, priceTo!!, type!!, category!!)
            refreshLayout!!.isRefreshing = false
        })

        //onClickListView
        list!!.onItemClickListener = AdapterView.OnItemClickListener { adapterView, view, i, l ->
            val space = (spaceList as ArrayList<Space>)[i]
            val intent = Intent(
                this,
                SpaceDetailsActivity::class.java
            ).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK)
            intent.putExtra(SID, space.sid)
            startActivity(intent)
        }
    }

    private fun setUPGClient() {
        mGoogleApiClient =
            GoogleApiClient.Builder(applicationContext)
                .enableAutoManage(this, 0, this)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .addApi(LocationServices.API)
                .build()

        mGoogleApiClient?.connect()
    }

    override fun onMapReady(p0: GoogleMap?) {

    }

    override fun onConnected(p0: Bundle?) {
        checkPermission()
    }

    private fun checkPermission() {
        val permissionLocation =
            ContextCompat.checkSelfPermission(
                this,
                Manifest.permission.ACCESS_FINE_LOCATION
            )

        val listPermission: MutableList<String> =
            ArrayList()
        if (permissionLocation != PackageManager.PERMISSION_GRANTED) {
            listPermission.add(Manifest.permission.ACCESS_FINE_LOCATION)

            if (listPermission.isNotEmpty()) {

                ActivityCompat.requestPermissions(
                    this,
                    listPermission.toTypedArray(), REQUEST_ID_MULTIPLE_PERMISSION
                )

            }
        } else {
            getMyLocation()
        }
    }

    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<out String>,
        grantResults: IntArray
    ) {
        val permissionLocation =
            ContextCompat.checkSelfPermission(
                this,
                Manifest.permission.ACCESS_FINE_LOCATION
            )

        if (permissionLocation == PackageManager.PERMISSION_GRANTED) {
            getMyLocation()
        } else {
            checkPermission()
        }
    }

    private fun getMyLocation() {
        if (mGoogleApiClient != null) {
            if (mGoogleApiClient!!.isConnected) {
                val permissionLocation =
                    ContextCompat.checkSelfPermission(
                        this,
                        Manifest.permission.ACCESS_FINE_LOCATION
                    )

                if (permissionLocation == PackageManager.PERMISSION_GRANTED) {
                    myLocation = LocationServices.FusedLocationApi.getLastLocation(mGoogleApiClient)
                    val locationRequest = LocationRequest()
                    //locationRequest.setInterval(3000);
                    //locationRequest.setFastestInterval(3000);
                    locationRequest.priority = LocationRequest.PRIORITY_HIGH_ACCURACY
                    val builder = LocationSettingsRequest.Builder()
                        .addLocationRequest(locationRequest)
                    builder.setAlwaysShow(true)
                    val statusPendingResult =
                        LocationServices.FusedLocationApi
                            .requestLocationUpdates(
                                mGoogleApiClient,
                                locationRequest,
                                this as LocationListener
                            )
                    val result =
                        LocationServices.SettingsApi
                            .checkLocationSettings(mGoogleApiClient, builder.build())
                    result.setResultCallback { result ->
                        val status =
                            result.status
                        when (status.statusCode) {
                            LocationSettingsStatusCodes.SUCCESS -> {
                                // All location settings are satisfied.
                                // You can initialize location requests here.
                                val permissionLocation =
                                    ContextCompat
                                        .checkSelfPermission(
                                            this,
                                            Manifest.permission.ACCESS_FINE_LOCATION
                                        )

                                if (permissionLocation == PackageManager.PERMISSION_GRANTED) {
                                    myLocation = LocationServices.FusedLocationApi
                                        .getLastLocation(mGoogleApiClient)
                                }
                            }
                            LocationSettingsStatusCodes.RESOLUTION_REQUIRED ->
                                // Location settings are not satisfied.
                                // But could be fixed by showing the user a dialog.
                                try {
                                    // Show the dialog by calling startResolutionForResult(),
                                    // and check the result in onActivityResult().
                                    // Ask to turn on GPS automatically
                                    status.startResolutionForResult(
                                        this, REQUEST_CHECK_SETTINGS_GPS
                                    )
                                } catch (e: IntentSender.SendIntentException) {
                                    // Ignore the error.
                                }
                            LocationSettingsStatusCodes.SETTINGS_CHANGE_UNAVAILABLE -> {
                            }
                        }
                    }
                }
            }
        }
    }


    override fun onConnectionSuspended(p0: Int) {

    }

    override fun onConnectionFailed(p0: ConnectionResult) {

    }

    override fun onLocationChanged(p0: Location?) {
        currentLatitude = p0!!.latitude
        currentLongitude = p0!!.longitude
//        Toast.makeText(
//            applicationContext,
//            "Lat: $currentLatitude\nLng: $currentLongitude",
//            Toast.LENGTH_SHORT
//        ).show()
        loadSpace(priceFrom!!,priceTo!!,type!!,category!!)
    }

    fun loadSpace(start: String, end: String, type: String, category: String) {
        //delay effect
        val progressDialog = ProgressDialog(this)
        progressDialog.setMessage("Loading nearby space on your area...")
        progressDialog.show()
        val url = "http://192.168.137.1:8080/osf/mobile/getspace.php"
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                //.makeText(getApplicationContext(), "Url: "+url, Toast.LENGTH_LONG).show();
                Log.e("Spaces: ", response)
                try {
                    //getting the whole json object from the response
                    val obj = JSONObject(response)
                    val success = obj.getString("success")

                    if (success == "1") {
                        progressDialog.dismiss()
                        //we have the array named hero inside the object
                        //so here we are getting that json array
                        val resultArray = obj.getJSONArray("data")

                        //now looping through all the elements of the json array
                        for (i in 0 until resultArray.length()) {
                            //getting the json object of the particular index inside the array
                            val jsonObject = resultArray.getJSONObject(i)
                            val lat = jsonObject.getDouble("lat")
                            val lng = jsonObject.getDouble("lng")
                            val final_distance: Float = distance(
                                currentLatitude!!,
                                currentLongitude!!,
                                lat,
                                lng
                            )
                            if (final_distance < 1500) {
                                val spaces = Space(
                                    jsonObject.getInt("sid"),
                                    jsonObject.getString("image"),
                                    jsonObject.getString("status"),
                                    jsonObject.getString("name"),
                                    jsonObject.getDouble("price"),
                                    jsonObject.getString("address")
                                )
                                spaceList!!.add(spaces)
                            }
                        }
                        //creating custom adapter object
                        adapter = spaceList?.let {
                            SpaceAdapter(
                                it,
                                this
                            )
                        }

                        //adding the adapter to listview
                        list!!.adapter = adapter
                    } else {
                        Toast.makeText(applicationContext, "No space found", Toast.LENGTH_SHORT).show()
                        progressDialog.dismiss()
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    progressDialog.dismiss()
                }
            },
            Response.ErrorListener { error -> //displaying the error in toast if occurrs
                Toast.makeText(applicationContext, error.message, Toast.LENGTH_SHORT)
                    .show()
                progressDialog.dismiss()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["start"] = start
                params["end"] = end
                params["type"] = type
                params["category"] = category

                return params
            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }

    private fun distance(
        currentlatitude: Double,
        currentlongitude: Double,
        originLat: Double,
        originLon: Double
    ): Float {
        val results = FloatArray(1)
        Location.distanceBetween(
            currentlatitude,
            currentlongitude,
            originLat,
            originLon,
            results
        )
        return results[0]
    }

    override fun onCreateOptionsMenu(menu: Menu): Boolean {
        menuInflater.inflate(R.menu.search, menu)
        val menuItem = menu.findItem(R.id.search)
        val searchView = menuItem.actionView as SearchView
        searchView.setOnQueryTextListener(object : SearchView.OnQueryTextListener {
            override fun onQueryTextSubmit(s: String): Boolean {
                return false
            }

            override fun onQueryTextChange(s: String): Boolean {
                if (TextUtils.isEmpty(s)) {
                    adapter!!.filter("")
                    list!!.clearTextFilter()
                } else {
                    adapter!!.filter(s)
                }
                return true
            }
        })
        return true
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> finish()
        }//action
        return super.onOptionsItemSelected(item)
    }

    override fun onRestart() {
        super.onRestart()
        //When BACK BUTTON is pressed, the activity on the stack is restarted
        //Do what you want on the refresh procedure here
        spaceList!!.clear()
        //Intent intent = new Intent(getApplicationContext(), HomeActivity.class);
        //startActivity(intent);
        //finish();
    }

    companion object {
        const val SID = "com.example.onlinespacefinder.SID"
        const val REQUEST_ID_MULTIPLE_PERMISSION = 0x2
        const val REQUEST_CHECK_SETTINGS_GPS = 0X1

    }
}
