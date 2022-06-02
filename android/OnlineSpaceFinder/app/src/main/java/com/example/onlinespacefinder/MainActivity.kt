package com.example.onlinespacefinder

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import com.android.volley.AuthFailureError
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import kotlinx.android.synthetic.main.activity_main.*
import org.json.JSONException
import org.json.JSONObject
import java.util.HashMap

class MainActivity : AppCompatActivity(), View.OnClickListener {

    private var username: String? = null
    private var password: String? = null
    private var sessionManager: SessionManager? = null
    private var id: Int? = null
    private var image: String? = null
    private var fname: String? = null
    private var lname: String? = null
    private var address: String? = null
    private var email: String? = null
    private var contact: String? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        //initialize
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_FULLSCREEN
        btnLogin.setOnClickListener(this)
        sessionManager = SessionManager(this)

        //set
        etUsername!!.editText!!.requestFocus()
    }

    fun register(view: View) {
        val intent = Intent(this, RegisterActivity::class.java)
        startActivity(intent)
    }

    fun validateUsername(): Boolean {
        if (username!!.isEmpty()) {
            etUsername!!.error = "This is required"
            return false
        } else {
            etUsername!!.error = null
            etUsername!!.isErrorEnabled = false
            return true
        }
    }

    fun validatePassword(): Boolean {
        if (password!!.isEmpty()) {
            etPassword!!.error = "This is required"
            return false
        } else {
            etPassword!!.error = null
            etPassword!!.isErrorEnabled = false
            return true
        }
    }

    override fun onClick(v: View?) {
        when (v?.id) {
            R.id.btnLogin -> {
                //parsing
                username = etUsername!!.editText!!.text.toString().trim()
                password = etPassword!!.editText!!.text.toString().trim()

                if(!validateUsername() or !validatePassword()) {
                    return
                }

                login()
            }
        }
    }

    fun login() {
        loading!!.setVisibility(View.VISIBLE)
        btnLogin!!.setVisibility(View.GONE)
        val url = "http://192.168.137.1:8080/osf/mobile/login.php"
        val stringRequest = object : StringRequest(
            Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Response: ", response);
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    val message = jsonObject.getString("message")
                    if (success == "1") {
                        loading!!.setVisibility(View.VISIBLE)
                        btnLogin!!.setVisibility(View.GONE)
                        val jsonArray = jsonObject.getJSONArray("data")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)
                            id = obj.getInt("user_id")
                            image = obj.getString("image")
                            fname = obj.getString("fname")
                            lname = obj.getString("lname")
                            address = obj.getString("address")
                            email = obj.getString("email")
                            contact = obj.getString("contact")
                            username = obj.getString("username")
                            sessionManager!!.createSession(id!!.toString(), image!!, fname!!, lname!!, address!!, email!!, contact!!, username!!)

                            val intent = Intent(applicationContext, HomeActivity::class.java)
                            startActivity(intent)
                            finish()
                        }
                    }
                    else if(success=="2") {
                        val builder = AlertDialog.Builder(this@MainActivity)

                        builder.setTitle("Failed to login")
                        builder.setMessage(message)

                        builder.setPositiveButton("OK") { dialog, which ->
                            // Do nothing but close the dialog
                            etUsername!!.editText!!.setText("")
                            etPassword!!.editText!!.setText("")
                            etUsername!!.editText!!.requestFocus()
                            dialog.dismiss()
                        }

                        val alert = builder.create()
                        alert.show()
                        loading!!.setVisibility(View.GONE)
                        btnLogin!!.setVisibility(View.VISIBLE)
                    } else if(success=="3") {
                        loading!!.setVisibility(View.VISIBLE)
                        btnLogin!!.setVisibility(View.GONE)
                        val jsonArray = jsonObject.getJSONArray("data")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)
                            id = obj.getInt("user_id")
                            image = obj.getString("image")
                            fname = obj.getString("fname")
                            lname = obj.getString("lname")
                            address = obj.getString("address")
                            email = obj.getString("email")
                            contact = obj.getString("contact")
                            username = obj.getString("username")
                            sessionManager!!.createSession(id!!.toString(), image!!, fname!!, lname!!, address!!, email!!, contact!!, username!!)

                            val intent = Intent(applicationContext, NotVerifiedActivity::class.java)
                            startActivity(intent)
                            finish()
                        }
                    } else {
//                      Toast.makeText(applicationContext, "Login error", Toast.LENGTH_SHORT).show()
                        val builder = AlertDialog.Builder(this@MainActivity)

                        builder.setTitle("Failed to login")
                        builder.setMessage(message)

                        builder.setPositiveButton("OK") { dialog, which ->
                            // Do nothing but close the dialog
                            etUsername!!.editText!!.setText("")
                            etPassword!!.editText!!.setText("")
                            etUsername!!.editText!!.requestFocus()
                            dialog.dismiss()
                        }

                        val alert = builder.create()
                        alert.show()
                        loading!!.setVisibility(View.GONE)
                        btnLogin!!.setVisibility(View.VISIBLE)
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(applicationContext, "Failed" + e.toString(), Toast.LENGTH_SHORT).show()
                    loading!!.visibility = View.GONE
                    btnLogin!!.visibility = View.VISIBLE
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(applicationContext, "Failed" + error.toString(), Toast.LENGTH_SHORT).show()
                loading!!.visibility = View.GONE
                btnLogin!!.visibility = View.VISIBLE
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
//                val params: MutableMap<String, String> = HashMap()
                params["username"] = username!!
                params["password"] = password!!

                return params
            }
        }
        AppController.Companion.getmInstance()!!.addToRequestQueue(stringRequest)
    }
}
