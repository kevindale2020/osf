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

class SpaceFeedbackAdapter(
    private val spaceFeedbackList: MutableList<SpaceFeedbacks>,
    private val mCtx: Context
) :
    ArrayAdapter<SpaceFeedbacks>(mCtx, R.layout.custom_space_feedback_layout, spaceFeedbackList) {
    private var inflater: LayoutInflater? = null
    private var image_url: String? = null
    private var stringBuilder: StringBuilder? = null
    private var imageLoader: Picasso? = null
    private val arrayList: MutableList<SpaceFeedbacks>

    init {
        this.arrayList = ArrayList()
        this.arrayList!!.addAll(spaceFeedbackList)
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
            convertView = inflater!!.inflate(R.layout.custom_space_feedback_layout, null, true)
        }

        var imageView = convertView!!.findViewById<ImageView>(R.id.image_view)
        var name = convertView!!.findViewById<TextView>(R.id.name)
        var space = convertView!!.findViewById<TextView>(R.id.space)
        var comment = convertView!!.findViewById<TextView>(R.id.comment)
        var rating = convertView!!.findViewById<RatingBar>(R.id.rating)
        var date = convertView!!.findViewById<TextView>(R.id.date)

        var spaceFeedbacks = spaceFeedbackList!![position]

        stringBuilder = StringBuilder("http://192.168.137.1:8080/osf/images/users/")
        stringBuilder!!.append(spaceFeedbacks.image)
        image_url = stringBuilder.toString()
        imageLoader!!.load(image_url).into(imageView)

        name.text = spaceFeedbacks.name
        space.text = spaceFeedbacks.space
        comment.text = spaceFeedbacks.comment
        rating.rating = spaceFeedbacks.rating
        date.text = spaceFeedbacks.date
        return convertView
    }
}
