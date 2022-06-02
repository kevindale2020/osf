package com.example.onlinespacefinder

import android.Manifest
import android.app.DatePickerDialog
import android.app.ProgressDialog
import android.content.DialogInterface
import android.content.Intent
import android.content.IntentSender
import android.content.pm.PackageManager
import android.graphics.Color
import android.location.Location
import android.os.Bundle
import android.util.Log
import android.view.Gravity
import android.view.MenuItem
import android.view.View
import android.widget.*
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import androidx.core.graphics.drawable.DrawableCompat
import androidx.core.view.isVisible
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.google.android.gms.common.ConnectionResult
import com.google.android.gms.common.api.GoogleApiClient
import com.google.android.gms.common.api.GoogleApiClient.ConnectionCallbacks
import com.google.android.gms.common.api.GoogleApiClient.OnConnectionFailedListener
import com.google.android.gms.location.*
import com.google.android.gms.maps.CameraUpdateFactory
import com.google.android.gms.maps.GoogleMap
import com.google.android.gms.maps.OnMapReadyCallback
import com.google.android.gms.maps.SupportMapFragment
import com.google.android.gms.maps.model.LatLng
import com.google.android.gms.maps.model.MarkerOptions
import kotlinx.android.synthetic.main.activity_space_details.*
import org.json.JSONException
import org.json.JSONObject
import java.util.*
import kotlin.collections.ArrayList

class SpaceDetailsActivity : AppCompatActivity(), OnMapReadyCallback, ConnectionCallbacks,
    OnConnectionFailedListener, LocationListener {
    private var sid: Int? = null
    private var image_url: String? = null
    private var main_url: String? = null
    private var spaceName: String? = null
    private var userid: Int? = null
    private var sessionManager: SessionManager? = null
    private var strDate: String? = null
    private var strTime: String? = null
    private var reason: String? = null
    private var tvDate: TextView? = null
    private var googleMap: GoogleMap? = null
    private var mGoogleApiClient: GoogleApiClient? = null
    private var myLocation: Location? = null
    private var currentLatitude: Double? = null
    private var currentLongitude: Double? = null
    private var imageUrls: MutableList<String>? = null
    private var adapter: ViewPagerAdapter? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_space_details)
        setUPGClient()

        //toolbar
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_FULLSCREEN
        supportActionBar?.setDisplayHomeAsUpEnabled(true)

        //for the session values
        sessionManager = SessionManager(this)
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        userid = user[SessionManager.USER_ID]!!.toInt()

        //arraylist
        imageUrls = ArrayList()
        adapter =
            ViewPagerAdapter(
                this,
                (imageUrls as ArrayList<String>)!!
            )

        //initialize map
        // Obtain the SupportMapFragment and get notified when the map is ready to be used.

        // Obtain the SupportMapFragment and get notified when the map is ready to be used.
        val mapFragment = supportFragmentManager
            .findFragmentById(R.id.map) as SupportMapFragment?
        mapFragment!!.getMapAsync(this)

        val intent = intent
        sid = intent.getIntExtra(SpaceActivity.SID, 0)

//        Toast.makeText(applicationContext, "SID ${sid} was passed", Toast.LENGTH_SHORT).show()

        getImages()

        showDetails()

        btnRent.setOnClickListener {
            val builder = AlertDialog.Builder(this@SpaceDetailsActivity)

            builder.setTitle("Rent Space")
            builder.setMessage("Are you sure you want to reserve ${spaceName}?")

            builder.setPositiveButton("YES",
                DialogInterface.OnClickListener { dialog, which -> // Do nothing but close the dialog
                    val builder = AlertDialog.Builder(this@SpaceDetailsActivity)
                    builder.setTitle("Confirm Reservation")
                    builder.setMessage("Please fill up below")

                    //Edit Text
                    val input = EditText(this@SpaceDetailsActivity)
                    val lp = LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.MATCH_PARENT,
                        LinearLayout.LayoutParams.WRAP_CONTENT
                    )
                    lp.setMargins(20, 0, 20, 0)
                    input.hint = "Reason for renting"
                    input.layoutParams = lp

                    //TextView
                    tvDate = TextView(this@SpaceDetailsActivity)
                    val lp2 = LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.WRAP_CONTENT,
                        LinearLayout.LayoutParams.WRAP_CONTENT
                    )
                    lp2.setMargins(0, 16, 0, 0)

                    //Button
                    val btnDate = Button(this@SpaceDetailsActivity)
                    val lp3 = LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.MATCH_PARENT,
                        LinearLayout.LayoutParams.WRAP_CONTENT
                    )
                    lp3.setMargins(20, 16, 20, 0)
                    btnDate.setTextColor(Color.parseColor("#ffffff"))
                    btnDate.setBackgroundColor(Color.parseColor("#48BBED"))
                    btnDate.text = "Select Date of Visit"
                    btnDate.layoutParams = lp3
                    btnDate.setOnClickListener { view ->
                        clickDatePicker(view)
                    }

                    //TimePicker
                    val timePicker = TimePicker(this@SpaceDetailsActivity)
                    val lp4 = LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.MATCH_PARENT,
                        LinearLayout.LayoutParams.WRAP_CONTENT
                    )
                    lp4.setMargins(20, 16, 20, 0)
                    timePicker.layoutParams = lp4
                    timePicker.setOnTimeChangedListener { view, hourOfDay, minute ->
                        var hourOfDay = hourOfDay
                        var format = ""
                        if (hourOfDay == 0) {
                            hourOfDay += 12
                            format = "AM"
                        } else if (hourOfDay == 12) {
                            format = "PM"
                        } else if (hourOfDay > 12) {
                            hourOfDay -= 12
                            format = "PM"
                        } else {
                            format = "AM"
                        }

                        val hour =
                            if (hourOfDay < 10) "0$hourOfDay" else hourOfDay.toString()
                        val min =
                            if (minute < 10) "0$minute" else minute.toString()
                        strTime =
                            "$hour : $min $format"
                    }
//
//                    builder.setView(input)
//                    builder.setView(btnDate)
                    val ll = LinearLayout(this)
                    ll.orientation = LinearLayout.VERTICAL
                    ll.addView(input)
                    ll.addView(tvDate)
                    ll.addView(btnDate)
                    ll.addView(timePicker)
                    builder.setView(ll)
                    builder.setPositiveButton("SUBMIT",
                        DialogInterface.OnClickListener { dialog, which -> // Do nothing but close the dialog
                        })
                    builder.setNegativeButton("CANCEL",
                        DialogInterface.OnClickListener { dialog, i -> dialog.dismiss() })
                    val alert: AlertDialog = builder.create()
                    alert.show()
                    alert.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener {
                        //validate
                        reason = input.text.toString().trim()
                        if (reason!!.isEmpty() || strDate!!.isEmpty() || strTime!!.isEmpty()) {
                            Toast.makeText(applicationContext, "Please fill-up all fields", Toast.LENGTH_SHORT).show()
                        } else {
                            rent(input.text.toString())
                            dialog.dismiss()
                        }
                    }
//                    dialog.dismiss()
                })

            builder.setNegativeButton("NO",
                DialogInterface.OnClickListener { dialog, i -> dialog.dismiss() })

            val alert: AlertDialog = builder.create()
            alert.show()
        }
        var first_click: Boolean = true
        icon_favorite.setOnClickListener {
            if (first_click) {
                DrawableCompat.setTint(
                    DrawableCompat.wrap(icon_favorite.getDrawable()),
                    ContextCompat.getColor(applicationContext, R.color.colorDanger)
                )
                addFavorites()
                first_click = false
            } else {
                DrawableCompat.setTint(
                    DrawableCompat.wrap(icon_favorite.getDrawable()),
                    ContextCompat.getColor(applicationContext, R.color.colorLightGray)
                )
                removeFavorites()
                first_click = true
            }
        }
    }

    fun feedback(view: View?) {
        val intent = Intent(this, SpaceFeedbackActivity::class.java)
        intent.putExtra(SID, sid!!)
        startActivity(intent)
    }

    fun getImages() {
        val url = "http://192.168.137.1:8080/osf/mobile/getimages.php"
        val time = System.currentTimeMillis().toInt()
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Images: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    val main = jsonObject.getString("main")
                    main_url = "http://192.168.137.1:8080/osf/images/spaces/$main?timestamp=$time"
                    imageUrls!!.add(main_url!!)
                    if (success == "1") {
                        val jsonArray = jsonObject.getJSONArray("images")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)
                            val stringBuilder =
                                StringBuilder("http://192.168.137.1:8080/osf/images/spaces/")
                            stringBuilder.append(obj.getString("image"))
                            stringBuilder.append("?timestamp=$time")
                            image_url = stringBuilder.toString()
                            imageUrls!!.add(image_url!!)
                        }
                        view_pager.adapter = adapter
                    }
                    //Toast.makeText(getApplicationContext(), success, Toast.LENGTH_SHORT).show();
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(applicationContext, "Failed $e", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                error.printStackTrace()
                Toast.makeText(applicationContext, "Failed $error", Toast.LENGTH_SHORT).show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["sid"] = sid.toString()
                return params
            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }

    fun showDetails() {
        val url = "http://192.168.137.1:8080/osf/mobile/details.php"
        val time = System.currentTimeMillis().toInt()
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Response: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    if (success == "1") {
                        val jsonArray = jsonObject.getJSONArray("data")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)

//                            val stringBuilder =
//                                StringBuilder("http://192.168.137.1:8080/osf/images/spaces/")
//                            stringBuilder.append(obj.getString("image"))
//                            stringBuilder.append("?timestamp=$time")
//                            image_url = stringBuilder.toString()
//
//                            Picasso.get().load(image_url).into(image_view_details)
                            status.text = obj.getString("status")
                            price.text = "₱${obj.getDouble("price").toString()}"
                            name.text = obj.getString("name")
                            address.text = obj.getString("address")
//                            value_sid.text = obj.getInt("sid").toString()
                            contact.text = obj.getString("contact")
                            description.text = obj.getString("description")
                            date.text = obj.getString("date")
                            value_owner.text = obj.getString("fname") + " " + obj.getString("lname")
                            value_email.text = obj.getString("email")
                            value_capacity.text = obj.getInt("capacity").toString()
                            spaceName = obj.getString("name")
                            currentLatitude = obj.getDouble("lat")
                            currentLongitude = obj.getDouble("lng")
                            if (obj.getString("counter") != "0") {
                                ratings.isVisible = true
                                avgRating.text =
                                    "${String.format("%.1f", obj.getDouble("ratings"))}/5"
                                ratings.rating = obj.getDouble("ratings").toFloat()
                                ratingLink.text = "Ratings & Reviews (${obj.getString("counter")})"
                            } else {
                                ratings.isVisible = false
                                ratingLink.text = "No reviews yet"
                            }

                            if (obj.getString("status") == "Reserved") {
                                status.setBackgroundColor(Color.parseColor("#f0ad4e"))
                                btnRent.isVisible = false
                            } else if (obj.getString("status") == "Rented") {
                                status.setBackgroundColor(Color.parseColor("#d9534f"))
                                btnRent.isVisible = false
                            } else {
                                status.setBackgroundColor(Color.parseColor("#5cb85c"))
                                btnRent.isEnabled = true
                            }

                            if (obj.getString("counter2") != "0") {
                                DrawableCompat.setTint(
                                    DrawableCompat.wrap(icon_favorite.getDrawable()),
                                    ContextCompat.getColor(applicationContext, R.color.colorDanger)
                                )
                            } else {
                                DrawableCompat.setTint(
                                    DrawableCompat.wrap(icon_favorite.getDrawable()),
                                    ContextCompat.getColor(
                                        applicationContext,
                                        R.color.colorLightGray
                                    )
                                )
                            }
                        }
                    }
                    //Toast.makeText(getApplicationContext(), success, Toast.LENGTH_SHORT).show();
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(applicationContext, "Failed $e", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                error.printStackTrace()
                Toast.makeText(applicationContext, "Failed $error", Toast.LENGTH_SHORT).show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["sid"] = sid.toString()
                return params
            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }


    fun rent(reason: String) {
        val progressDialog = ProgressDialog(this)
        progressDialog.setMessage("Please wait...")
        progressDialog.show()
        val url = "http://192.168.137.1:8080/osf/mobile/rent.php"
        val time = System.currentTimeMillis().toInt()
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Response: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    val message = jsonObject.getString("message")
                    if (success == "1") {
                        val builder = AlertDialog.Builder(this@SpaceDetailsActivity)

                        builder.setTitle("Space Rent")
                        builder.setMessage(message)

                        builder.setPositiveButton("OK") { dialog, which ->
                            // Do nothing but close the dialog
                            progressDialog.dismiss()
                            dialog.dismiss()
                            val intent = Intent(applicationContext, HomeActivity::class.java)
                            startActivity(intent)
                            finish()
                        }

                        val alert = builder.create()
                        alert.show()
                    } else {
                        val builder = AlertDialog.Builder(this@SpaceDetailsActivity)

                        builder.setTitle("Space Rent")
                        builder.setMessage(message)

                        builder.setPositiveButton("OK") { dialog, which ->
                            // Do nothing but close the dialog
                            progressDialog.dismiss()
                            dialog.dismiss()
                        }

                        val alert = builder.create()
                        alert.show()
                    }
                    //Toast.makeText(getApplicationContext(), success, Toast.LENGTH_SHORT).show();
                } catch (e: JSONException) {
                    progressDialog.dismiss()
                    e.printStackTrace()
                    Toast.makeText(applicationContext, "Failed $e", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                progressDialog.dismiss()
                error.printStackTrace()
                Toast.makeText(applicationContext, "Failed $error", Toast.LENGTH_SHORT).show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["sid"] = sid.toString()
                params["userid"] = userid.toString()
                params["date"] = strDate!!
                params["time"] = strTime!!
                params["reason"] = reason
                return params
            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }

    fun addFavorites() {
        val url = "http://192.168.137.1:8080/osf/mobile/addfavorites.php"
        val time = System.currentTimeMillis().toInt()
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Response: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    val message = jsonObject.getString("message")
                    if (success == "1") {
                        Toast.makeText(applicationContext, message, Toast.LENGTH_SHORT).show();
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(applicationContext, "Failed $e", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                error.printStackTrace()
                Toast.makeText(applicationContext, "Failed $error", Toast.LENGTH_SHORT).show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["uid"] = userid.toString()
                params["sid"] = sid.toString()
                return params
            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }

    fun removeFavorites() {
        val url = "http://192.168.137.1:8080/osf/mobile/removefavorites.php"
        val time = System.currentTimeMillis().toInt()
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Response: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    val message = jsonObject.getString("message")
                    if (success == "1") {
                        Toast.makeText(applicationContext, message, Toast.LENGTH_SHORT).show();
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(applicationContext, "Failed $e", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                error.printStackTrace()
                Toast.makeText(applicationContext, "Failed $error", Toast.LENGTH_SHORT).show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["uid"] = userid.toString()
                params["sid"] = sid.toString()
                return params
            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }

//    fun validatePassword(): Boolean {
//        if (password!!.isEmpty()) {
//            etPassword!!.error = "This is required"
//            return false
//        } else {
//            etPassword!!.error = null
//            etPassword!!.isErrorEnabled = false
//            return true
//        }
//    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> finish()
        }//action
        return super.onOptionsItemSelected(item)
    }

    fun clickDatePicker(view: View) {
        val myCalendar = Calendar.getInstance()
        val year = myCalendar.get(Calendar.YEAR)
        val month = myCalendar.get(Calendar.MONTH)
        val day = myCalendar.get(Calendar.DAY_OF_MONTH)
        val dpd = DatePickerDialog(
            this,
            DatePickerDialog.OnDateSetListener { view, year, month, dayOfMonth ->
                val selectedDate = "$year/${month + 1}/$dayOfMonth"

//            val sdf = SimpleDateFormat("yyyy-MM-dd")
                strDate = selectedDate
                tvDate!!.setTextColor(Color.parseColor("#D3D3D3"))
                tvDate!!.textSize = 16.0f
                tvDate!!.text = strDate
                tvDate!!.gravity = Gravity.CENTER
            },
            year,
            month,
            day
        )
        dpd.datePicker.minDate = Date().time - 1000
        dpd.show()
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
        googleMap = p0
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
        //mMap.animateCamera(CameraUpdateFactory.newLatLngZoom(new LatLng(currentLatitude, currentLongitude), 14.0f));
        val current = LatLng(currentLatitude!!, currentLongitude!!)
        val markerOptions = MarkerOptions()
        markerOptions.title(spaceName)
        markerOptions.position(current)
        googleMap!!.addMarker(markerOptions)
        //mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(current,14.0f));
        googleMap!!.moveCamera(CameraUpdateFactory.newLatLngZoom(current, 14.0f))
//        val builder = LatLngBounds.Builder()
//        builder.include(current)
//        val bounds = builder.build()
//        val width = this.resources.displayMetrics.widthPixels
//        val height = this.resources.displayMetrics.heightPixels
//        val padding = (width * 0.30).toInt()
//        val cu = CameraUpdateFactory.newLatLngBounds(bounds, width, height, padding)
//        googleMap!!.moveCamera(cu)
//        Toast.makeText(applicationContext, "$currentLatitude, $currentLongitude", Toast.LENGTH_SHORT).show()
    }

    companion object {
        const val REQUEST_ID_MULTIPLE_PERMISSION = 0x2
        const val REQUEST_CHECK_SETTINGS_GPS = 0X1
        const val SID = "com.example.onlinespacefinder.SID"

    }
}

