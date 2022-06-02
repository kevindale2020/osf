package com.example.onlinespacefinder

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.widget.AdapterView
import android.widget.AdapterView.OnItemClickListener
import android.widget.ListView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.FragmentTransaction
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.example.onlinespacefinder.ui.history.HistoryFragment
import com.example.onlinespacefinder.ui.reservation.ReservationFragment
import org.json.JSONException
import org.json.JSONObject
import java.util.*
import kotlin.collections.ArrayList
import kotlin.collections.set


class NotificationsActivity : AppCompatActivity() {
    private var userid: Int? = null
    private var sessionManager: SessionManager? = null
    private var notificationList: MutableList<Notifications>? = null
    private var list: ListView? = null
    private var adapter: NotificationAdapter? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_notifications)

        //toolbar
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_FULLSCREEN
        supportActionBar?.setDisplayHomeAsUpEnabled(true)

        //for the session values
        sessionManager = SessionManager(this)
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        userid = user[SessionManager.USER_ID]!!.toInt()

        list = findViewById(R.id.list)
        notificationList = ArrayList()
        adapter =
            NotificationAdapter(
                (notificationList as ArrayList<Notifications>)!!,
                this
            )
        loadNotifications()


        //onClickListView
        list!!.onItemClickListener = AdapterView.OnItemClickListener { adapterView, view, i, l ->
            var notifications = notificationList!![i]
//            Toast.makeText(applicationContext, notifications.type.toString(), Toast.LENGTH_SHORT).show()
            if(notifications.type==1) {
                val fragmentTransaction: FragmentTransaction =
                    supportFragmentManager.beginTransaction()
                fragmentTransaction.replace(R.id.notificationsContener, ReservationFragment()).commit()
            } else if(notifications.type==2) {
                val fragmentTransaction: FragmentTransaction =
                    supportFragmentManager.beginTransaction()
                fragmentTransaction.replace(R.id.notificationsContener, HistoryFragment()).commit()
            } else if(notifications.type==3) {
//                Toast.makeText(applicationContext, "Coming soon this feature!", Toast.LENGTH_SHORT).show()
                val intent = Intent(applicationContext, FeedbackActivity::class.java)
                intent.putExtra(OWNER, notifications.ownerid)
                intent.putExtra(NOTIFICATIONID, notifications.id)
                startActivity(intent)
                finish()
            } else {
                return@OnItemClickListener
            }
        }

        //onClickListView
//        list!!.onItemClickListener = OnItemClickListener { adapterView, view, i, l ->
//            val notifications = notificationList!![i]
//            if (notifications.type == 1) {
//                val fragmentTransaction: FragmentTransaction =
//                    supportFragmentManager.beginTransaction()
//                fragmentTransaction.replace(R.id.notificationsContener, ReservationFragment()).commit()
//            } else if (notifications.type == 2) {
//                val fragmentTransaction: FragmentTransaction =
//                    supportFragmentManager.beginTransaction()
//                fragmentTransaction.replace(R.id.notificationsContener, HistoryFragment()).commit()
//            } else if (notifications.type == 3) {
//                val intent = Intent(applicationContext, FeedbackActivity::class.java)
//                startActivity(intent)
//            } else {
//                //nothing happens
//                return@OnItemClickListener
//            }
//        }
    }


    fun loadNotifications() {
        val url = "http://192.168.137.1:8080/osf/mobile/getnotifications.php"
        val time = System.currentTimeMillis().toInt()
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Notifications:", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    if (success == "1") {
                        val jsonArray = jsonObject.getJSONArray("data")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)
                            val notifications = Notifications(
                                obj.getInt("id"),
                                obj.getInt("type"),
                                obj.getString("subject"),
                                obj.getString("content"),
                                obj.getString("date"),
                                obj.getInt("ownerid")
                            )
                            notificationList!!.add(notifications)
                            //creating custom adapter object
                            adapter =
                                NotificationAdapter(
                                    (notificationList as ArrayList<Notifications>)!!,
                                    this
                                )

                            //adding the adapter to listview
                            list!!.adapter = adapter
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
                params["id"] = userid.toString()

                return params
            }
        }
        AppController.Companion.getmInstance()!!.addToRequestQueue(stringRequest)
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> finish()
        }//action
        return super.onOptionsItemSelected(item)
    }

    companion object {
        const val OWNER = "com.example.onlinespacefinder.OWNER"
        const val NOTIFICATIONID = "com.example.onlinespacefinder.NOTIFICATIONID"
    }
}
