package com.example.onlinespacefinder.ui.feedbacks

import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ListView
import android.widget.TextView
import android.widget.Toast
import androidx.fragment.app.Fragment
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProviders
import androidx.navigation.findNavController
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.example.onlinespacefinder.*
import org.json.JSONException
import org.json.JSONObject
import java.util.HashMap

class FeedbackFragment : Fragment() {
    private var list: ListView? = null
    private var myFeedbacksList: MutableList<MyFeedbacks>? = null
    private var adapter: MyFeedbackAdapter? = null
    private var sessionManager: SessionManager? = null
    private var user_id: Int? = null
    private lateinit var feedbackViewModel: FeedbackViewModel

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        val root = inflater.inflate(R.layout.fragment_feedbacks, container, false)

        //for the session values
        sessionManager = context?.let { SessionManager(it) }
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        user_id = user[SessionManager.USER_ID]!!.toInt()

        myFeedbacksList = ArrayList()
        list = root.findViewById(R.id.list)
        adapter = context?.let {
            MyFeedbackAdapter(
                (myFeedbacksList as ArrayList<MyFeedbacks>)!!,
                it
            )
        }

        loadMyFeedbacks()

        return root
    }

    fun loadMyFeedbacks() {

        val url = "http://192.168.137.1:8080/osf/mobile/myfeedbacks.php"
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("My Feedback Results: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")


                    if (success == "1") {
                        val jsonArray = jsonObject.getJSONArray("data")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)
                            val myFeedbacks = MyFeedbacks(
                                obj.getInt("id"),
                                obj.getString("image"),
                                obj.getString("name"),
                                obj.getString("space"),
                                obj.getString("comment"),
                                obj.getDouble("rating").toFloat(),
                                obj.getString("date")
                            )
                            myFeedbacksList!!.add(myFeedbacks)
                        }
                        adapter = context?.let {
                            MyFeedbackAdapter(
                                (myFeedbacksList as ArrayList<MyFeedbacks>)!!,
                                it
                            )
                        }
                        list!!.adapter = adapter
                        adapter!!.notifyDataSetChanged()

                    }

                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(context, "Failed $e", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(context, "Failed $error", Toast.LENGTH_SHORT).show()
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
}
