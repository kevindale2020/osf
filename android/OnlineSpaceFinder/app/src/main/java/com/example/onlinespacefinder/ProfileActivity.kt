package com.example.onlinespacefinder

import android.app.Activity
import android.app.ProgressDialog
import android.content.Intent
import android.graphics.Bitmap
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.provider.MediaStore
import android.util.Base64
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.squareup.picasso.Picasso
import kotlinx.android.synthetic.main.activity_profile.*
import org.json.JSONException
import org.json.JSONObject
import java.io.ByteArrayOutputStream
import java.io.IOException
import java.util.HashMap

class ProfileActivity : AppCompatActivity() {

    private var sessionManager: SessionManager? = null
    private var user_id: Int? = null
    private var image_url: String? = null
    private var fname: String? = null
    private var lname: String? = null
    private var address: String? = null
    private var email: String? = null
    private var contact: String? = null
    private var username: String? = null
    private var bitmap: Bitmap? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profile)

        //toolbar
        window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_FULLSCREEN
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.elevation = 0f

        //for the session values
        sessionManager = SessionManager(this)
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        user_id = user[SessionManager.USER_ID]!!.toInt()

        loadProfile()

        iconEdit.setOnClickListener {
            chooseFile()
        }

        btnUpdate.setOnClickListener {
            fname = etFirstName!!.editText!!.text.toString().trim()
            lname = etLastName!!.editText!!.text.toString().trim()
            address = etAddress!!.editText!!.text.toString().trim()
            email = etEmail!!.editText!!.text.toString().trim()
            contact = etContact!!.editText!!.text.toString().trim()
            username = etUsername!!.editText!!.text.toString().trim()

            if(bitmap!=null) {
                updateProfile(getStringImage(bitmap!!))
            } else {
                updateProfile("")
            }
        }
    }

    fun loadProfile() {
        val url = "http://192.168.137.1:8080/osf/mobile/userprofile.php"
        val time = System.currentTimeMillis().toInt()
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
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
                            etFirstName.editText!!.setText(obj.getString("fname"))
                            etLastName.editText!!.setText(obj.getString("lname"))
                            etAddress.editText!!.setText(obj.getString("address"))
                            etEmail.editText!!.setText(obj.getString("email"))
                            etContact.editText!!.setText(obj.getString("contact"))
                            etUsername.editText!!.setText(obj.getString("username"))
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

    fun updateProfile(imageURL: String) {
        val progressDialog = ProgressDialog(this)
        progressDialog.setMessage("Updating...")
        progressDialog.show()
        val url = "http://192.168.137.1:8080/osf/mobile/updateprofile.php"
        val stringRequest = object: StringRequest(Request.Method.POST, url,
            Response.Listener {response ->
                try {
                    Log.e("Response: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    if(success == "1") {
                        loadProfile()
                        progressDialog.dismiss()
                        val builder = AlertDialog.Builder(this@ProfileActivity)

                        builder.setTitle("Update Profile")
                        builder.setMessage("Updated successfully")

                        builder.setPositiveButton("OK") { dialog, which ->
                            // Do nothing but close the dialog
                            progressDialog.dismiss()
                            dialog.dismiss()
                        }

                        val alert = builder.create()
                        alert.show()
                    }
                } catch ( e : JSONException) {
                    progressDialog.dismiss()
                    e.printStackTrace()
                    Toast.makeText(applicationContext, "Failed $e", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                progressDialog.dismiss()
                Toast.makeText(applicationContext, "Failed $error", Toast.LENGTH_SHORT).show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
//                val params: MutableMap<String, String> = HashMap()
                params["id"] = user_id.toString()
                params["image"] = imageURL
                params["fname"] = fname!!
                params["lname"] = lname!!
                params["address"] = address!!
                params["email"] = email!!
                params["contact"] = contact!!
                params["username"] = username!!

                return params
            }
        }
        AppController.Companion.getmInstance()!!.addToRequestQueue(stringRequest)
    }

    fun chooseFile() {
        val intent = Intent()
        intent.type = "image/*"
        intent.action = Intent.ACTION_GET_CONTENT
        startActivityForResult(Intent.createChooser(intent, "Select Picture"), 1)
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)
        if (requestCode == 1 && resultCode == Activity.RESULT_OK && data != null && data.data != null) {
            val filepath = data.data
            try {

                bitmap = MediaStore.Images.Media.getBitmap(contentResolver, filepath)
                imageView!!.setImageBitmap(bitmap)


            } catch (e: IOException) {
                e.printStackTrace()
            }

            //UploadPicture(user_id, getStringImage(bitmap));
        }
    }

    fun getStringImage(bitmap: Bitmap): String {

        val byteArrayOutputStream = ByteArrayOutputStream()
        bitmap.compress(Bitmap.CompressFormat.JPEG, 100, byteArrayOutputStream)

        val imageByteArray = byteArrayOutputStream.toByteArray()

        return Base64.encodeToString(imageByteArray, Base64.DEFAULT)
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> finish()
        }//action
        return super.onOptionsItemSelected(item)
    }
}

