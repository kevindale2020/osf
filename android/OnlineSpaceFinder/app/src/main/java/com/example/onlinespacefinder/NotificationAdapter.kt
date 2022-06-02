package com.example.onlinespacefinder

import android.annotation.SuppressLint
import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ArrayAdapter
import android.widget.ImageView
import android.widget.TextView
import com.squareup.picasso.Picasso
import java.util.ArrayList

class NotificationAdapter(
    private val notificationList: MutableList<Notifications>,
    private val mCtx: Context
) :
    ArrayAdapter<Notifications>(mCtx, R.layout.custom_notifications_layout, notificationList) {
    private var inflater: LayoutInflater? = null
    private val arrayList: MutableList<Notifications>

    init {
        this.arrayList = ArrayList()
        this.arrayList!!.addAll(notificationList)
    }

    @SuppressLint("SetTextI18n", "ResourceAsColor")
    override fun getView(position: Int, convertView: View?, parent: ViewGroup): View {
        var convertView = convertView
        inflater = LayoutInflater.from(mCtx)
        if (inflater == null) {
            inflater = mCtx.getSystemService(Context.LAYOUT_INFLATER_SERVICE) as LayoutInflater
        }

        if (convertView == null) {
            convertView = inflater!!.inflate(R.layout.custom_notifications_layout, null, true)
        }

        var imageView = convertView!!.findViewById<ImageView>(R.id.image_view)
        var subject = convertView!!.findViewById<TextView>(R.id.subject)
        var content = convertView!!.findViewById<TextView>(R.id.content)
        var date = convertView!!.findViewById<TextView>(R.id.date)

        var notifications = notificationList!![position]

        subject.text = notifications.subject
        content.text = notifications.content
        date.text = notifications.date

        return convertView
    }
}