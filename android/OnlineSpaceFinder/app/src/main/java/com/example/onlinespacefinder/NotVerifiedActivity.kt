package com.example.onlinespacefinder

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import androidx.appcompat.app.AlertDialog
import kotlinx.android.synthetic.main.activity_not_verified.*

class NotVerifiedActivity : AppCompatActivity() {
    private var sessionManager: SessionManager? = null
    private var user_id: Int? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_not_verified)

        //for the session values
        sessionManager = SessionManager(this)
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        user_id = user[SessionManager.USER_ID]!!.toInt()

        btnLogout.setOnClickListener {
            val builder = AlertDialog.Builder(this@NotVerifiedActivity)

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
}
