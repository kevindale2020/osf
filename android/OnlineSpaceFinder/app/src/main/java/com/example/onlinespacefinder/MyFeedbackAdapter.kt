package com.example.onlinespacefinder

import android.annotation.SuppressLint
import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ArrayAdapter
import android.widget.ImageView
import android.widget.RatingBar
import android.widget.TextView
import com.squareup.picasso.Picasso
import java.util.ArrayList

class MyFeedbackAdapter(
    private val myFeedbacksList: MutableList<MyFeedbacks>,
    private val mCtx: Context
) :
    ArrayAdapter<MyFeedbacks>(mCtx, R.layout.custom_feedback_layout, myFeedbacksList) {
    private var inflater: LayoutInflater? = null
    private var image_url: String? = null
    private var stringBuilder: StringBuilder? = null
    private var imageLoader: Picasso? = null
    private val arrayList: MutableList<MyFeedbacks>

    init {
        this.arrayList = ArrayList()
        this.arrayList!!.addAll(myFeedbacksList)
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
            convertView = inflater!!.inflate(R.layout.custom_feedback_layout, null, true)
        }

        var imageView = convertView!!.findViewById<ImageView>(R.id.image_view)
        var name = convertView!!.findViewById<TextView>(R.id.name)
        var space = convertView!!.findViewById<TextView>(R.id.space)
        var comment = convertView!!.findViewById<TextView>(R.id.comment)
        var rating = convertView!!.findViewById<RatingBar>(R.id.rating)
        var date = convertView!!.findViewById<TextView>(R.id.date)

        var myFeedbacks = myFeedbacksList!![position]

        stringBuilder = StringBuilder("http://192.168.137.1:8080/osf/images/users/")
        stringBuilder!!.append(myFeedbacks.image)
        image_url = stringBuilder.toString()
        imageLoader!!.load(image_url).into(imageView)

        name.text = myFeedbacks.name
        space.text = myFeedbacks.space
        comment.text = myFeedbacks.comment
        rating.rating = myFeedbacks.rating
        date.text = myFeedbacks.date
        return convertView
    }
}
