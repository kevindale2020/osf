package com.example.onlinespacefinder.ui.password

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.fragment.app.Fragment
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import com.android.volley.AuthFailureError
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.example.onlinespacefinder.AppController
import com.example.onlinespacefinder.MainActivity
import com.example.onlinespacefinder.R
import com.example.onlinespacefinder.SessionManager
import com.google.android.material.textfield.TextInputLayout
import kotlinx.android.synthetic.main.activity_register.*
import kotlinx.android.synthetic.main.fragment_password.*
import kotlinx.android.synthetic.main.fragment_password.loading
import org.json.JSONException
import org.json.JSONObject
import java.util.HashMap

class PasswordFragment : Fragment() {

    private lateinit var passwordViewModel: PasswordViewModel
    private var etCurrentPassword: TextInputLayout? = null
    private var etNewPassword: TextInputLayout? = null
    private var etConfirmPassword: TextInputLayout? = null
    private var btnChangePassword: Button? = null
    private var current: String? = null
    private var new: String? = null
    private var confirm: String? = null
    private var sessionManager: SessionManager? = null
    private var user_id: Int? = null

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        val root = inflater.inflate(R.layout.fragment_password, container, false)

        //for the session values
        sessionManager = context?.let { SessionManager(it) }
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        user_id = user[SessionManager.USER_ID]!!.toInt()

        etCurrentPassword = root.findViewById(R.id.etCurrentPassword)
        etNewPassword = root.findViewById(R.id.etNewPassword)
        etConfirmPassword = root.findViewById(R.id.etConfirmPassword)
        btnChangePassword = root.findViewById(R.id.btnChangePassword)

        //request focus
        etCurrentPassword!!.editText!!.requestFocus()

        btnChangePassword!!.setOnClickListener {
            current = etCurrentPassword!!.editText!!.text.toString().trim()
            new = etNewPassword!!.editText!!.text.toString().trim()
            confirm = etConfirmPassword!!.editText!!.text.toString().trim()

            if (!validateCurrent() or !validateNew() or !validateConfirm()) {
                return@setOnClickListener
            }

            changePassword()

        }
        return root
    }

    fun validateCurrent(): Boolean {
        if (current!!.isEmpty()) {
            etCurrentPassword!!.error = "This is required"
            return false
        } else {
            etCurrentPassword!!.error = null
            etCurrentPassword!!.isErrorEnabled = false
            return true
        }
    }

    fun validateNew(): Boolean {
        if (new!!.isEmpty()) {
            etNewPassword!!.error = "This is required"
            return false
        } else if (!new.equals(confirm)) {
            etNewPassword!!.error = "Password does not match with the confirm password"
            return false
        } else if (new!!.length < 8) {
            etNewPassword!!.error = "Password must be at least 8-16 characters"
            return false
        } else if (new!!.length > 16) {
            etNewPassword!!.error = "Password is too long"
            return false
        } else {
            etNewPassword!!.error = null
            etNewPassword!!.isErrorEnabled = false
            return true
        }
    }

    fun validateConfirm(): Boolean {
        if (confirm!!.isEmpty()) {
            etConfirmPassword!!.error = "This is required"
            return false
        }  else if (!new.equals(confirm)) {
            etNewPassword!!.error = "Password does not match"
            return false
        } else {
            etConfirmPassword!!.error = null
            etConfirmPassword!!.isErrorEnabled = false
            return true
        }
    }

    private fun changePassword() {
        loading!!.visibility = View.VISIBLE
        btnChangePassword!!.visibility = View.GONE
        val url = "http://192.168.137.1:8080/osf/mobile/changepassword.php"
        // url = "http://192.168.43.44:8080/IRO/Android/register.php"
        val stringRequest = object : StringRequest(
            Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Change Password Results", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    val message = jsonObject.getString("message")
                    if (success == "1") {
                        etCurrentPassword!!.editText!!.text = null
                        etNewPassword!!.editText!!.text = null
                        etConfirmPassword!!.editText!!.text = null
                        val builder = AlertDialog.Builder(requireContext())

                        builder.setTitle("Change Password")
                        builder.setMessage(message)

                        builder.setPositiveButton("OK") { dialog, which ->
                            // Do nothing but close the dialog
                            dialog.dismiss()
                        }

                        val alert = builder.create()
                        alert.show()

                        loading!!.visibility = View.GONE
                        btnChangePassword!!.visibility = View.VISIBLE

                    } else {
                        etCurrentPassword!!.error = message
                        loading!!.visibility = View.GONE
                        btnChangePassword!!.visibility = View.VISIBLE
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(requireContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT)
                        .show()
                    loading!!.visibility = View.GONE
                    btnChangePassword!!.visibility = View.VISIBLE
                }
            },
            Response.ErrorListener { error ->
                error.printStackTrace()
                Toast.makeText(requireContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT)
                    .show()
                loading!!.visibility = View.GONE
                btnChangePassword!!.visibility = View.VISIBLE
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["id"] = user_id.toString()
                params["current"] = current!!
                params["new"] = new!!

                return params

            }
        }

        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }
}
