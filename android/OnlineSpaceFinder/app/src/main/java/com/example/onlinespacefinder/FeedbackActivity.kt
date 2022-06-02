package com.example.onlinespacefinder

import android.app.ProgressDialog
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.widget.Button
import android.widget.RatingBar
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.example.onlinespacefinder.R
import com.example.onlinespacefinder.SessionManager
import com.example.onlinespacefinder.ui.home.HomeFragment
import com.google.android.material.textfield.TextInputLayout
import kotlinx.android.synthetic.main.activity_register.*
import org.json.JSONException
import org.json.JSONObject
import java.util.HashMap

class FeedbackActivity : AppCompatActivity() {
    private var userid: Int? = null
    private var sessionManager: SessionManager? = null
    private var btnSubmit: Button? = null
    private var ratingBar: RatingBar? = null
    private var etFeedback: TextInputLayout? = null
    private var rate: String? = null
    private var feedback: String ? = null
    private var ownerid: Int? = null
    private var notificationid: Int? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_feedback)

        //toolbar
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_FULLSCREEN
        supportActionBar?.setDisplayHomeAsUpEnabled(true)

        //for the session values
        sessionManager = SessionManager(this)
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        userid = user[SessionManager.USER_ID]!!.toInt()

        ratingBar = findViewById(R.id.rating)
        etFeedback = findViewById(R.id.etFeedback)
        btnSubmit = findViewById(R.id.btnSubmit)

        val intent = intent
        ownerid = intent.getIntExtra(NotificationsActivity.OWNER, 0)
        notificationid = intent.getIntExtra(NotificationsActivity.NOTIFICATIONID, 0)
        //Toast.makeText(applicationContext, "$ownerid was passed\n$notificationid was also passed", Toast.LENGTH_SHORT).show()

        btnSubmit!!.setOnClickListener {
            feedback = etFeedback!!.editText!!.text.toString().trim()
            rate = ratingBar!!.rating.toString()

            if(!validateFeedback() or !validateRate()) {
                return@setOnClickListener
            }

            rate()

        }
    }

    fun validateFeedback(): Boolean {
        if (feedback!!.isEmpty()) {
            etFeedback!!.error = "This is required"
            return false
        } else {
            etFeedback!!.error = null
            etFeedback!!.isErrorEnabled = false
            return true
        }
    }

    fun validateRate(): Boolean {
        return !rate.equals("0")
    }

    fun rate() {
        val progressDialog = ProgressDialog(this)
        progressDialog.setMessage("Please wait...")
        progressDialog.show()
        val url = "http://192.168.137.1:8080/osf/mobile/rate.php"
        val time = System.currentTimeMillis().toInt()
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Rates:", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    val message = jsonObject.getString("message")
                    if (success == "1") {
                        progressDialog.dismiss()
                        val builder = AlertDialog.Builder(this@FeedbackActivity)

                        builder.setTitle("Feedback")
                        builder.setMessage(message)

                        builder.setPositiveButton("OK") { dialog, which ->
                            // Do nothing but close the dialog
                            val intent = Intent(applicationContext, HomeActivity::class.java)
                            startActivity(intent)
                            finish()
                            dialog.dismiss()
                        }

                        val alert = builder.create()
                        alert.show()

                        loading!!.visibility = View.GONE
                        btnSubmit!!.visibility = View.VISIBLE
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
                params["userid"] = userid.toString()
                params["spaceid"] = ownerid.toString()
                params["notificationid"] = notificationid.toString()
                params["rate"] = rate.toString()
                params["comment"] = feedback.toString()
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
