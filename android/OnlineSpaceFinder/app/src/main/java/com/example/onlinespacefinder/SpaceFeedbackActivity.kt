package com.example.onlinespacefinder

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.widget.ListView
import android.widget.Toast
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import org.json.JSONException
import org.json.JSONObject
import java.util.HashMap

class SpaceFeedbackActivity : AppCompatActivity() {
    private var list: ListView? = null
    private var spaceFeedbacksList: MutableList<SpaceFeedbacks>? = null
    private var adapter: SpaceFeedbackAdapter? = null
    private var userid: Int? = null
    private var sessionManager: SessionManager? = null
    private var sid: Int? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_space_feedback)

        //toolbar
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_FULLSCREEN
        supportActionBar?.setDisplayHomeAsUpEnabled(true)

        //for the session values
        sessionManager = SessionManager(this)
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        userid = user[SessionManager.USER_ID]!!.toInt()

        val intent = intent
        sid = intent.getIntExtra(SpaceDetailsActivity.SID, 0)
//        Toast.makeText(applicationContext, "$sid was passed", Toast.LENGTH_SHORT).show()

        list = findViewById(R.id.list)
        spaceFeedbacksList = ArrayList()
        adapter =
            SpaceFeedbackAdapter(
                (spaceFeedbacksList as ArrayList<SpaceFeedbacks>)!!,
                this
            )

        loadSpaceFeedbacks()
    }

    fun loadSpaceFeedbacks() {

        val url = "http://192.168.137.1:8080/osf/mobile/spacefeedbacks.php"
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("SpaceFeedback Results: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")


                    if (success == "1") {
                        val jsonArray = jsonObject.getJSONArray("data")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)
                            val spaceFeedbacks = SpaceFeedbacks(
                                obj.getInt("id"),
                                obj.getString("image"),
                                obj.getString("name"),
                                obj.getString("space"),
                                obj.getString("comment"),
                                obj.getDouble("rating").toFloat(),
                                obj.getString("date")
                            )
                            spaceFeedbacksList!!.add(spaceFeedbacks)
                        }
                        adapter =
                            SpaceFeedbackAdapter(
                                (spaceFeedbacksList as ArrayList<SpaceFeedbacks>)!!,
                                this
                            )

                        list!!.adapter = adapter
                        adapter!!.notifyDataSetChanged()

                    }

                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(applicationContext, "Failed $e", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener
            { error ->
                Toast.makeText(applicationContext, "Failed $error", Toast.LENGTH_SHORT).show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
//                val params: MutableMap<String, String> = HashMap()
                params["sid"] = sid.toString()
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
}
