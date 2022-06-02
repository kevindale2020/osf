package com.example.onlinespacefinder.ui.reservation

import android.app.ProgressDialog
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ListView
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.fragment.app.Fragment
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.example.onlinespacefinder.*
import org.json.JSONException
import org.json.JSONObject
import java.util.HashMap

class ReservationFragment : Fragment() {

    private lateinit var reservationViewModel: ReservationViewModel
    private var refreshLayout: SwipeRefreshLayout? = null
    private var list: ListView? = null
    private var reservationList: MutableList<Reservation>? = null
    private var adapter: ReservationAdapter? = null
    private var sessionManager: SessionManager? = null
    private var user_id: Int? = null

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {

        val root = inflater.inflate(R.layout.fragment_reservation, container, false)

        //for the session values
        sessionManager = context?.let { SessionManager(it) }
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        user_id = user[SessionManager.USER_ID]!!.toInt()

        reservationList = ArrayList()
        list = root.findViewById(R.id.list)
        adapter = context?.let {
            ReservationAdapter(
                (reservationList as ArrayList<Reservation>)!!,
                it
            )
        }

        refreshLayout = root.findViewById(R.id.swipe_refresh_layout)

        loadReservation()
        refreshLayout!!.setOnRefreshListener(SwipeRefreshLayout.OnRefreshListener {
            reservationList!!.clear()
            loadReservation()
            refreshLayout!!.isRefreshing = false
        })


        return root
    }

    fun loadReservation() {

        val url = "http://192.168.137.1:8080/osf/mobile/getreservations.php"
        val stringRequest = object : StringRequest(Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Reservation Results: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")


                    if (success == "1") {
                        val jsonArray = jsonObject.getJSONArray("data")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)
                            val reservation = Reservation(
                                obj.getInt("rid"),
                                obj.getString("date"),
                                obj.getString("status"),
                                obj.getString("image"),
                                obj.getString("space"),
                                obj.getString("reason"),
                                obj.getString("dateVisit"),
                                obj.getDouble("price"),
                                obj.getString("address"),
                                obj.getString("time")
                            )
                            reservationList!!.add(reservation)
                        }
                        adapter = context?.let {
                            ReservationAdapter(
                                (reservationList as ArrayList<Reservation>)!!,
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
