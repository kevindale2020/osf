package com.example.onlinespacefinder

import android.annotation.SuppressLint
import android.app.ProgressDialog
import android.content.Context
import android.content.DialogInterface
import android.content.Intent
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.*
import androidx.appcompat.app.AlertDialog
import androidx.core.view.isVisible
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.squareup.picasso.Picasso
import org.json.JSONException
import org.json.JSONObject
import org.w3c.dom.Text
import java.util.ArrayList
import java.util.HashMap

class RentHistoryAdapter(
    private val rentHistoryList: MutableList<RentHistory>,
    private val mCtx: Context
) :
    ArrayAdapter<RentHistory>(mCtx, R.layout.custom_history_layout, rentHistoryList) {
    private var inflater: LayoutInflater? = null
    private var image_url: String? = null
    private var stringBuilder: StringBuilder? = null
    private var imageLoader: Picasso? = null
    private val arrayList: MutableList<RentHistory>

    init {
        this.arrayList = ArrayList()
        this.arrayList!!.addAll(rentHistoryList)
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
            convertView = inflater!!.inflate(R.layout.custom_history_layout, null, true)
        }

        var id = convertView!!.findViewById<TextView>(R.id.tv_id)
        var imageView = convertView!!.findViewById<ImageView>(R.id.image_view)
        var date1 = convertView!!.findViewById<TextView>(R.id.tv_date1)
        var status = convertView!!.findViewById<TextView>(R.id.tv_status)
        var space = convertView!!.findViewById<TextView>(R.id.tv_space)
        var price = convertView!!.findViewById<TextView>(R.id.price)
        var address = convertView!!.findViewById<TextView>(R.id.address)
        var date2 = convertView!!.findViewById<TextView>(R.id.tv_date2)
        var lengthStay = convertView!!.findViewById<TextView>(R.id.tv_length_stay)

        var rentHistory = rentHistoryList!![position]

        stringBuilder = StringBuilder("http://192.168.137.1:8080/osf/images/spaces/")
        stringBuilder!!.append(rentHistory.image)
        image_url = stringBuilder.toString()
        imageLoader!!.load(image_url).into(imageView)

        id.text = "#${rentHistory.id.toString()}"
        date1.text = "Stayed on ${rentHistory.date1}"
        status.text = rentHistory.status
        price.text = "₱ ${rentHistory.price}"
        address.text = rentHistory.address
        space.text = rentHistory.name
        date2.text = "Left on ${rentHistory.date2}"
        lengthStay.text = "Length of Stay: ${rentHistory.lengthStay.toString()}"

        return convertView
    }
}