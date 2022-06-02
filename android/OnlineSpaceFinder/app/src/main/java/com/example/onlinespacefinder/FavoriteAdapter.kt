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

class FavoriteAdapter(
    private val favoriteList: MutableList<Favorites>,
    private val mCtx: Context
) :
    ArrayAdapter<Favorites>(mCtx, R.layout.custom_favorite_layout, favoriteList) {
    private var inflater: LayoutInflater? = null
    private var image_url: String? = null
    private var stringBuilder: StringBuilder? = null
    private var imageLoader: Picasso? = null
    private val arrayList: MutableList<Favorites>

    init {
        this.arrayList = ArrayList()
        this.arrayList!!.addAll(favoriteList)
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
            convertView = inflater!!.inflate(R.layout.custom_favorite_layout, null, true)
        }

        var id = convertView!!.findViewById<TextView>(R.id.tv_id)
        var imageView = convertView!!.findViewById<ImageView>(R.id.image_view)
        var date = convertView!!.findViewById<TextView>(R.id.tv_date)
        var space = convertView!!.findViewById<TextView>(R.id.tv_space)
        var price = convertView!!.findViewById<TextView>(R.id.price)
        var address = convertView!!.findViewById<TextView>(R.id.address)
        var favorites = favoriteList!![position]

        stringBuilder = StringBuilder("http://192.168.137.1:8080/osf/images/spaces/")
        stringBuilder!!.append(favorites.image)
        image_url = stringBuilder.toString()
        imageLoader!!.load(image_url).into(imageView)

        id.text = "#${favorites.id}"
        date.text = "Added on ${favorites.date}"
        space.text = favorites.space
        price.text = "₱ ${favorites.price}"
        address.text = favorites.address

        return convertView
    }
}