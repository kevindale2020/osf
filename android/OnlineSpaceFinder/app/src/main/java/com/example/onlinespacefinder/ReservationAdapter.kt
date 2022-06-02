package com.example.onlinespacefinder

import android.annotation.SuppressLint
import android.app.ProgressDialog
import android.content.Context
import android.content.DialogInterface
import android.content.Intent
import android.graphics.Color
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.*
import androidx.appcompat.app.AlertDialog
import androidx.core.content.ContextCompat.startActivity
import androidx.core.view.isVisible
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.squareup.picasso.Picasso
import org.json.JSONException
import org.json.JSONObject
import java.util.*

class ReservationAdapter(
    private val reservationList: MutableList<Reservation>,
    private val mCtx: Context
) :
    ArrayAdapter<Reservation>(mCtx, R.layout.custom_reservation_layout, reservationList) {
    private var inflater: LayoutInflater? = null
    private var image_url: String? = null
    private var stringBuilder: StringBuilder? = null
    private var imageLoader: Picasso? = null
    private val arrayList: MutableList<Reservation>

    init {
        this.arrayList = ArrayList()
        this.arrayList!!.addAll(reservationList)
        this.imageLoader = Picasso.get()
    }

    @SuppressLint("SetTextI18n", "ResourceAsColor")
    override fun getView(position: Int, convertView: View?, parent: ViewGroup): View {
        var convertView = convertView
        inflater = LayoutInflater.from(mCtx)
        if (inflater == null) {
            inflater = mCtx.getSystemService(Context.LAYOUT_INFLATER_SERVICE) as LayoutInflater
        }

        if (convertView == null) {
            convertView = inflater!!.inflate(R.layout.custom_reservation_layout, null, true)
        }

        var rid = convertView!!.findViewById<TextView>(R.id.tv_rid)
        var imageView = convertView!!.findViewById<ImageView>(R.id.image_view)
        var date = convertView!!.findViewById<TextView>(R.id.tv_date)
        var status = convertView!!.findViewById<TextView>(R.id.tv_status)
        var space = convertView!!.findViewById<TextView>(R.id.tv_space)
        var price = convertView!!.findViewById<TextView>(R.id.tv_price)
        var address = convertView!!.findViewById<TextView>(R.id.address)
        var visit = convertView!!.findViewById<TextView>(R.id.tv_visit)
        var reason = convertView!!.findViewById<TextView>(R.id.tv_reason)
        var btnCancel = convertView!!.findViewById<Button>(R.id.btnCancel)

        var reservation = reservationList!![position]

        btnCancel.isVisible =
            !(reservation.status == "Approved" || reservation.status == "Cancelled" || reservation.status == "Rejected" || reservation.status == "Closed")

        btnCancel.setOnClickListener {
            val builder = AlertDialog.Builder(mCtx)

            builder.setTitle("Cancellation")
            builder.setMessage("Are you sure you want to cancel your reservation for ${reservation.space}?")

            builder.setPositiveButton("YES",
                DialogInterface.OnClickListener { dialog, which -> // Do nothing but close the dialog
                    val builder = AlertDialog.Builder(mCtx)
                    builder.setTitle("Confirm cancellation")
                    builder.setMessage("Reason for cancelling")

                    //edit text
                    val input = EditText(mCtx)
                    val lp = LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.MATCH_PARENT,
                        LinearLayout.LayoutParams.MATCH_PARENT
                    )
                    input.layoutParams = lp
                    builder.setView(input)
                    builder.setPositiveButton("SUBMIT",
                        DialogInterface.OnClickListener { dialog, which -> // Do nothing but close the dialog
                            cancel(reservation.rid,input.text.toString())
                            dialog.dismiss()
                        })
                    builder.setNegativeButton("CANCEL",
                        DialogInterface.OnClickListener { dialog, i -> dialog.dismiss() })
                    val alert: AlertDialog = builder.create()
                    alert.show()
                    dialog.dismiss()
                })

            builder.setNegativeButton("NO",
                DialogInterface.OnClickListener { dialog, i -> dialog.dismiss() })

            val alert: AlertDialog = builder.create()
            alert.show()
        }

        stringBuilder = StringBuilder("http://192.168.137.1:8080/osf/images/spaces/")
        stringBuilder!!.append(reservation.image)
        image_url = stringBuilder.toString()
        imageLoader!!.load(image_url).into(imageView)

        rid.text = "Reservation#${reservation.rid.toString()}"
        date.text = "Reserved on ${reservation.date}"
        status.text = reservation.status
        space.text = reservation.space
        price.text = "₱${reservation.price.toString()}"
        address.text = reservation.address
        visit.text = "Date of Visit on ${reservation.date2} at ${reservation.time}"
        reason.text = "Reason: ${reservation.reason}"

        return convertView
    }

    fun cancel(rid: Int, reason: String) {
        val progressDialog = ProgressDialog(mCtx)
        progressDialog.setMessage("Please wait...")
        progressDialog.show()
        val url = "http://192.168.137.1:8080/osf/mobile/cancel.php"
        val time = System.currentTimeMillis().toInt()
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Response: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    val message = jsonObject.getString("message")
                    if (success == "1") {
                        val builder = AlertDialog.Builder(mCtx)

                        builder.setTitle("Cancellation")
                        builder.setMessage(message)

                        builder.setPositiveButton("OK") { dialog, which ->
                            // Do nothing but close the dialog
                            progressDialog.dismiss()
                            val intent = Intent(mCtx, HomeActivity::class.java)
                            mCtx.startActivity(intent)
                            dialog.dismiss()
                        }

                        val alert = builder.create()
                        alert.show()
                    }
                } catch (e: JSONException) {
                    progressDialog.dismiss()
                    e.printStackTrace()
                    Toast.makeText(mCtx, "Failed $e", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                progressDialog.dismiss()
                error.printStackTrace()
                Toast.makeText(mCtx, "Failed $error", Toast.LENGTH_SHORT).show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["rid"] = rid.toString()
                params["reason"] = reason
                return params
            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }
}