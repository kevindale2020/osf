package com.example.onlinespacefinder

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.google.android.material.textfield.TextInputLayout
import kotlinx.android.synthetic.main.activity_register.*
import org.json.JSONException
import org.json.JSONObject
import java.util.HashMap

class RegisterActivity : AppCompatActivity(), View.OnClickListener {
    private var fname: String? = null
    private var lname: String? = null
    private var address: String? = null
    private var email: String? = null
    private var contact: String? = null
    private var username: String? = null
    private var password: String? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)

        //initialize
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_FULLSCREEN
        supportActionBar!!.title = "Register"
        supportActionBar!!.setDisplayHomeAsUpEnabled(true)
        btnRegister.setOnClickListener(this)

        //set
        etFirstName!!.editText!!.requestFocus()
    }

    fun login(view: View) {
        val intent = Intent(this, MainActivity::class.java)
        startActivity(intent)
    }

    fun validateFirstName(): Boolean {
        if (fname!!.isEmpty()) {
            etFirstName!!.error = "This is required"
            return false
        } else {
            etFirstName!!.error = null
            etFirstName!!.isErrorEnabled = false
            return true
        }
    }

    fun validateLastName(): Boolean {
        if (lname!!.isEmpty()) {
            etLastName!!.error = "This is required"
            return false
        } else {
            etLastName!!.error = null
            etLastName!!.isErrorEnabled = false
            return true
        }
    }

    fun validateAddress(): Boolean {
        if (address!!.isEmpty()) {
            etAddress!!.error = "This is required"
            return false
        } else {
            etAddress!!.error = null
            etAddress!!.isErrorEnabled = false
            return true
        }
    }

    fun validateEmail(): Boolean {
        if (email!!.isEmpty()) {
            etEmail!!.error = "This is required"
            return false
        } else {
            etEmail!!.error = null
            etEmail!!.isErrorEnabled = false
            return true
        }
    }

    fun validateContact(): Boolean {
        if (contact!!.isEmpty()) {
            etContact!!.error = "This is required"
            return false
        } else {
            etContact!!.error = null
            etContact!!.isErrorEnabled = false
            return true
        }
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
        } else if (password!!.length < 8) {
            etPassword!!.error = "Password must be at least 8-16 characters"
            return false
        } else if (password!!.length > 16) {
            etPassword!!.error = "Password is too long"
            return false
        } else {
            etPassword!!.error = null
            etPassword!!.isErrorEnabled = false
            return true
        }
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> finish()
        }//action
        return super.onOptionsItemSelected(item)
    }

    override fun onClick(v: View?) {
        when (v?.id) {
            R.id.btnRegister -> {

                fname = etFirstName!!.editText!!.text.toString().trim()
                lname = etLastName!!.editText!!.text.toString().trim()
                address = etAddress!!.editText!!.text.toString().trim()
                email = etEmail!!.editText!!.text.toString().trim()
                contact = etContact!!.editText!!.text.toString().trim()
                username = etUsername!!.editText!!.text.toString().trim()
                password = etPassword!!.editText!!.text.toString().trim()

                if (!validateFirstName() or !validateLastName() or !validateAddress() or !validateEmail() or !validateContact() or !validateUsername() or !validatePassword()) {
                    return
                }

                register()
            }
        }
    }

    private fun register() {
        loading!!.visibility = View.VISIBLE
        btnRegister!!.visibility = View.GONE
        val url = "http://192.168.137.1:8080/osf/mobile/register.php"
        // url = "http://192.168.43.44:8080/IRO/Android/register.php"
        val stringRequest = object : StringRequest(
            Method.POST, url,
            Response.Listener { response ->
                try {
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    val message = jsonObject.getString("message")
                    if (success == "1") {
                        val builder = AlertDialog.Builder(this@RegisterActivity)

                        builder.setTitle("Registration")
                        builder.setMessage(message)

                        builder.setPositiveButton("OK") { dialog, which ->
                            // Do nothing but close the dialog
                            val intent = Intent(applicationContext, MainActivity::class.java)
                            startActivity(intent)
                            finish()
                            //dialog.dismiss()
                        }

                        val alert = builder.create()
                        alert.show()

                        loading!!.visibility = View.GONE
                        btnRegister!!.visibility = View.VISIBLE

                    } else if (success == "2") {
                        etUsername!!.error = message
                        loading!!.visibility = View.GONE
                        btnRegister!!.visibility = View.VISIBLE
                    } else {
                        etEmail!!.error = message
                        loading!!.visibility = View.GONE
                        btnRegister!!.visibility = View.VISIBLE
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(applicationContext, "Failed" + e.toString(), Toast.LENGTH_SHORT)
                        .show()
                    loading!!.visibility = View.GONE
                    btnRegister!!.visibility = View.VISIBLE
                }
            },
            Response.ErrorListener { error ->
                error.printStackTrace()
                Toast.makeText(applicationContext, "Failed" + error.toString(), Toast.LENGTH_SHORT)
                    .show()
                loading!!.visibility = View.GONE
                btnRegister!!.visibility = View.VISIBLE
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["fname"] = fname!!
                params["lname"] = lname!!
                params["address"] = address!!
                params["email"] = email!!
                params["contact"] = contact!!
                params["username"] = username!!
                params["password"] = password!!

                return params

            }
        }

        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }
}
