package com.example.onlinespacefinder

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.graphics.Color
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ArrayAdapter
import android.widget.ImageView
import android.widget.TextView
import com.squareup.picasso.Picasso
import java.util.*

class SpaceAdapter(private val spaceList: MutableList<Space>, private val mCtx: Context) :
    ArrayAdapter<Space>(mCtx, R.layout.custom_space_layout, spaceList) {
    private var inflater: LayoutInflater? = null
    private var image_url: String? = null
    private var stringBuilder: StringBuilder? = null
    private var imageLoader: Picasso? = null
    private val arrayList: MutableList<Space>

    init {
        this.arrayList = ArrayList()
        this.arrayList!!.addAll(spaceList)
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
            convertView = inflater!!.inflate(R.layout.custom_space_layout, null, true)
        }

        var imageView = convertView!!.findViewById<ImageView>(R.id.image_view)
        var name = convertView!!.findViewById<TextView>(R.id.name)
        var status = convertView!!.findViewById<TextView>(R.id.status)
        var price = convertView!!.findViewById<TextView>(R.id.price)
        var address = convertView!!.findViewById<TextView>(R.id.address)

        var space = spaceList!![position]

        if (space.status == "Reserved") {
            status.setBackgroundColor(Color.parseColor("#f0ad4e"))
        } else if (space.status == "Rented") {
            status.setBackgroundColor(Color.parseColor("#d9534f"))
        } else {
            status.setBackgroundColor(Color.parseColor("#5cb85c"))
        }

        stringBuilder = StringBuilder("http://192.168.137.1:8080/osf/images/spaces/")
        stringBuilder!!.append(space.image)
        image_url = stringBuilder.toString()
        imageLoader!!.load(image_url).into(imageView)

        name.text = space.name
        status.text = space.status
        price.text = "₱ ${space.price}"
        address.text = space.address

        return convertView
    }

    fun filter(charText: String) {
        var charText = charText
        charText = charText.toLowerCase(Locale.getDefault())
        spaceList.clear()
        if (charText.length == 0) {
            spaceList.addAll(arrayList)
        } else {
            for (space in arrayList) {
                if (space.name.toLowerCase(Locale.getDefault())
                        .contains(charText) || space.price.toString()
                        .toLowerCase(Locale.getDefault()).contains(charText)
                ) {
                    /*
                        if(pets.getStatus_id()==2) {
                            cardView.setVisibility(View.VISIBLE);
                            //next.setEnabled(false);
                            next.setClickable(false);
                        } else {
                            cardView.setVisibility(View.GONE);
                            //next.setEnabled(false);
                            next.setClickable(true);
                        }
                        */
                    spaceList.add(space)
                }
            }
        }
        notifyDataSetChanged()
    }

    companion object {
        val SID = "com.example.onlinespacefinder"
    }

}