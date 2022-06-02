package com.example.onlinespacefinder

import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import java.util.*

class SessionManager(var context: Context) {
    var sharedPreferences: SharedPreferences
    var editor: SharedPreferences.Editor
    var PRIVATE_MODE = 0
    fun createSession(
        id: String?,
        image: String?,
        fname: String?,
        lname: String?,
        address: String?,
        email: String?,
        contact: String?,
        username: String?
    ) {
        editor.putBoolean(LOGIN, true)
        editor.putString(USER_ID, id)
        editor.putString(USER_IMAGE, image)
        editor.putString(FIRST_NAME, fname)
        editor.putString(LAST_NAME, lname)
        editor.putString(ADDRESS, address)
        editor.putString(EMAIL, email)
        editor.putString(CONTACT, contact)
        editor.putString(USERNAME, username)
        editor.apply()
    }

    val isLoggin: Boolean
        get() = sharedPreferences.getBoolean(LOGIN, false)

    fun checkLogin() {
        if (!isLoggin) {
            val intent = Intent(context, MainActivity::class.java)
            context.startActivity(intent)
            (context as HomeActivity).finish()
        }
    }

    val userDetails: HashMap<String, String?>
        get() {
            val user =
                HashMap<String, String?>()
            user[USER_ID] = sharedPreferences.getString(USER_ID, null)
            user[USER_IMAGE] = sharedPreferences.getString(USER_IMAGE, null)
            user[FIRST_NAME] = sharedPreferences.getString(FIRST_NAME, null)
            user[LAST_NAME] = sharedPreferences.getString(LAST_NAME, null)
            user[ADDRESS] = sharedPreferences.getString(ADDRESS, null)
            user[EMAIL] = sharedPreferences.getString(EMAIL, null)
            user[CONTACT] = sharedPreferences.getString(CONTACT, null)
            user[USERNAME] = sharedPreferences.getString(USERNAME, null)
            return user
        }

    fun logout() {
        editor.clear()
        editor.commit()
        val intent = Intent(context, MainActivity::class.java)
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP)
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK)
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK)
        context.startActivity(intent)
        //((HomeActivity) context).finish();
    }

    companion object {
        private const val PREF_NAME = "LOGIN"
        private const val LOGIN = "IS_LOGIN"
        const val USER_ID = "USER_ID"
        const val USER_IMAGE = "USER_IMAGE"
        const val FIRST_NAME = "FIRST_NAME"
        const val LAST_NAME = "LAST_NAME"
        const val NICKNAME = "NICKNAME"
        const val OCCUPATION = "OCCUPATION"
        const val ADDRESS = "ADDRESS"
        const val EMAIL = "EMAIL"
        const val CONTACT = "CONTACT"
        const val USERNAME = "USERNAME"
    }

    init {
        sharedPreferences =
            context.getSharedPreferences(PREF_NAME, PRIVATE_MODE)
        editor = sharedPreferences.edit()
    }
}