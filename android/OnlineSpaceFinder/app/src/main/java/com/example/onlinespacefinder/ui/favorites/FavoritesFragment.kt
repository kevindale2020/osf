package com.example.onlinespacefinder.ui.favorites

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.AdapterView
import android.widget.ListView
import android.widget.Toast
import androidx.fragment.app.Fragment
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.example.onlinespacefinder.*
import org.json.JSONException
import org.json.JSONObject
import java.util.*
import kotlin.collections.ArrayList
import kotlin.collections.Map
import kotlin.collections.MutableList
import kotlin.collections.set

class FavoritesFragment : Fragment() {
    private var list: ListView? = null
    private var favoriteList: MutableList<Favorites>? = null
    private var adapter: FavoriteAdapter? = null
    private var sessionManager: SessionManager? = null
    private var user_id: Int? = null
    private lateinit var favoritesViewModel: FavoritesViewModel

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        val root = inflater.inflate(R.layout.fragment_favorites, container, false)

        //for the session values
        sessionManager = context?.let { SessionManager(it) }
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        user_id = user[SessionManager.USER_ID]!!.toInt()

        favoriteList = ArrayList()
        list = root.findViewById(R.id.list)
        adapter = context?.let {
            FavoriteAdapter(
                (favoriteList as ArrayList<Favorites>)!!,
                it
            )
        }

        loadFavorites()

        list!!.onItemClickListener = AdapterView.OnItemClickListener { adapterView, view, i, l ->
            val favorites = (favoriteList as java.util.ArrayList<Favorites>)[i]
            val intent = Intent(
                requireContext(),
                SpaceDetailsActivity::class.java
            ).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK)
            intent.putExtra(SpaceActivity.SID, favorites.sid)
            startActivity(intent)
        }


        return root
    }


    fun loadFavorites() {

        val url = "http://192.168.137.1:8080/osf/mobile/favorites.php"
        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Favorites Results: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")


                    if (success == "1") {
                        val jsonArray = jsonObject.getJSONArray("data")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)
                            val favorites = Favorites(
                                obj.getInt("id"),
                                obj.getString("date"),
                                obj.getString("image"),
                                obj.getString("space"),
                                obj.getDouble("price"),
                                obj.getString("address"),
                                obj.getInt("sid")
                            )
                            favoriteList!!.add(favorites)
                        }
                        adapter = context?.let {
                            FavoriteAdapter(
                                (favoriteList as ArrayList<Favorites>)!!,
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
                params["uid"] = user_id.toString()
                return params
            }
        }
        AppController.Companion.getmInstance()!!.addToRequestQueue(stringRequest)
    }

//    override fun onResume() {
//        //other stuff
//        super.onResume()
//        favoriteList!!.clear()
//        loadFavorites()
//    }

    companion object {
        const val SID = "com.example.onlinespacefinder.SID"
    }
}
