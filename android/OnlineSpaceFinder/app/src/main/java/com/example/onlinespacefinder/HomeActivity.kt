package com.example.onlinespacefinder

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.Menu
import android.view.View
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.widget.Toolbar
import androidx.core.view.MenuItemCompat
import androidx.navigation.findNavController
import androidx.navigation.ui.AppBarConfiguration
import androidx.navigation.ui.navigateUp
import androidx.navigation.ui.setupActionBarWithNavController
import androidx.navigation.ui.setupWithNavController
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.example.onlinespacefinder.SessionManager.Companion.FIRST_NAME
import com.example.onlinespacefinder.SessionManager.Companion.USER_ID
import com.squareup.picasso.Picasso
import kotlinx.android.synthetic.main.activity_home.*
import kotlinx.android.synthetic.main.content_home.*
import org.json.JSONException
import org.json.JSONObject
import java.util.*

class HomeActivity : AppCompatActivity() {

    private lateinit var appBarConfiguration: AppBarConfiguration
    private var sessionManager: SessionManager? = null
    private var user_id: Int? = null
    private var test: String? = null
    private var image_url: String? = null
    private var imageView: ImageView? = null
    private var tvFullname: TextView? = null
    private var iconEdit: ImageView? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_home)
        val toolbar: Toolbar = findViewById(R.id.toolbar)
        setSupportActionBar(toolbar)

        //for the session values
        sessionManager = SessionManager(this)
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        user_id = user[USER_ID]!!.toInt()
        test = user[FIRST_NAME]!!.toString()

//        Toast.makeText(applicationContext, "Welcome $test", Toast.LENGTH_SHORT).show()

        //Side Navigation View
        val navController = findNavController(R.id.nav_host_fragment)
        val view: View
        // Passing each menu ID as a set of Ids because each
        // menu should be considered as top level destinations.
        appBarConfiguration = AppBarConfiguration(
            setOf(
                R.id.navigation_home,
                R.id.nav_reservation,
                R.id.nav_history,
                R.id.nav_password,
                R.id.navigation_favorite,
                R.id.navigation_feedback
            ), drawer_layout
        )
        setupActionBarWithNavController(navController, appBarConfiguration)
        side_nav_view.setupWithNavController(navController)
        side_nav_view.setNavigationItemSelectedListener { menuItem ->
            when(menuItem.itemId) {
                R.id.nav_reservation->{
                    navController.navigate(R.id.nav_reservation)
                }
                R.id.nav_history->{
                    navController.navigate(R.id.nav_history)
                }
                R.id.nav_password->{
                    navController.navigate(R.id.nav_password)
                }
                R.id.nav_logout->{
                    val builder = AlertDialog.Builder(this@HomeActivity)

                    builder.setTitle("Logout")
                    builder.setMessage("Are you sure you want to logout?")

                    builder.setPositiveButton("YES") { dialog, which ->
                        // Do nothing but close the dialog
                        sessionManager!!.logout()
                        finish()
                        dialog.dismiss()
                    }

                    builder.setNegativeButton("NO") { dialog, i -> dialog.dismiss() }

                    val alert = builder.create()
                    alert.show()
                }
            }
            false
        }

        view = side_nav_view.getHeaderView(0)
        imageView = view.findViewById(R.id.imageView)
        tvFullname = view.findViewById(R.id.tvFullname)
        iconEdit = view.findViewById(R.id.iconEdit)

        iconEdit!!.setOnClickListener {
            Toast.makeText(this, "Hello World", Toast.LENGTH_SHORT).show()
            val intent = Intent(this, ProfileActivity::class.java )
//            intent.putExtra(Constant.NAME, name) sample pass data to intent
            startActivity(intent)
        }


        //Bottom Navigation View
        bottom_nav_view.setupWithNavController(navController)
        bottom_nav_view.setOnNavigationItemSelectedListener { menuItem ->
            when (menuItem.itemId) {
                R.id.navigation_favorite -> {
                    navController.navigate(R.id.navigation_favorite)
                }
                R.id.navigation_home -> {
                    navController.navigate(R.id.navigation_home)
                }
                R.id.navigation_feedback -> {
//                    val builder = AlertDialog.Builder(this@HomeActivity)
//
//                    builder.setTitle("Logout")
//                    builder.setMessage("Are you sure you want to logout?")
//
//                    builder.setPositiveButton("YES") { dialog, which ->
//                        // Do nothing but close the dialog
//                        sessionManager!!.logout()
//                        finish()
//                        dialog.dismiss()
//                    }
//
//                    builder.setNegativeButton("NO") { dialog, i -> dialog.dismiss() }
//
//                    val alert = builder.create()
//                    alert.show()
                    navController.navigate(R.id.navigation_feedback)
                }
            }
            false
        }

        loadUserProfile()
    }

    fun loadUserProfile() {
        val url = "http://192.168.137.1:8080/osf/mobile/userprofile.php"
        val time = System.currentTimeMillis().toInt()
        val stringRequest = object : StringRequest(Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("kevin2", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    if (success == "1") {
                        val jsonArray = jsonObject.getJSONArray("data")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)
                            if (obj.getString("image") == "") {
                                val stringBuilder =
                                    StringBuilder("http://192.168.137.1:8080/osf/images/users/")
                                stringBuilder.append("user_none.png")
                                image_url = stringBuilder.toString()
                            } else {
                                val stringBuilder =
                                    StringBuilder("http://192.168.137.1:8080/osf/images/users/")
                                stringBuilder.append(obj.getString("image"))
                                stringBuilder.append("?timestamp=" + time.toString())
                                image_url = stringBuilder.toString()
                            }
                            Picasso.get().load(image_url).into(imageView)
                            tvFullname!!.setText(obj.getString("fullname"))
                        }
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(applicationContext, "Failed $e", Toast.LENGTH_SHORT)
                        .show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(applicationContext, "Failed $error", Toast.LENGTH_SHORT)
                    .show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
//                val params: MutableMap<String, String> = HashMap()
                params["id"] = user_id.toString()

                return params
            }
        }
        AppController.Companion.getmInstance()!!.addToRequestQueue(stringRequest)
    }

    override fun onSupportNavigateUp(): Boolean {
        val navController = findNavController(R.id.nav_host_fragment)
        return navController.navigateUp(appBarConfiguration) || super.onSupportNavigateUp()
    }

    override fun onRestart() {
        super.onRestart()
        //When BACK BUTTON is pressed, the activity on the stack is restarted
        //Do what you want on the refresh procedure here
       loadUserProfile()
        //Intent intent = new Intent(getApplicationContext(), HomeActivity.class);
        //startActivity(intent);
        //finish();
    }

}
