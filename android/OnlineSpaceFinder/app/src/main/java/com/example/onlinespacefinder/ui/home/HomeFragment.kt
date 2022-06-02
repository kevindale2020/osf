package com.example.onlinespacefinder.ui.home

import android.app.PendingIntent
import android.content.Intent
import android.graphics.BitmapFactory
import android.graphics.Color
import android.graphics.Typeface
import android.graphics.drawable.ColorDrawable
import android.os.Bundle
import android.os.Handler
import android.util.Log
import android.view.*
import android.widget.*
import android.widget.AdapterView.OnItemSelectedListener
import androidx.core.app.NotificationCompat
import androidx.core.app.NotificationManagerCompat
import androidx.core.view.MenuItemCompat
import androidx.fragment.app.Fragment
import com.android.volley.AuthFailureError
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.example.onlinespacefinder.*
import com.example.onlinespacefinder.AppController.Companion.CHANNEL_1_ID
import kotlinx.android.synthetic.main.fragment_home.*
import org.json.JSONException
import org.json.JSONObject
import java.util.*


class HomeFragment : Fragment() {
    private var priceFrom: String? = null
    private var priceTo: String? = null
    private var type: String? = null
    private var category: String? = null
    private var btnSearch: Button? = null
    private var flag: Boolean = false
    private var flag2: Boolean? = false
    private var flag3: Boolean? = false
    private var flag4: Boolean? = false
    private var handler: Handler? = null
    private var userid: Int? = null
    private var sessionManager: SessionManager? = null
    private var subject: String? = null
    private var content: String? = null
    private var tvNotificationBadge: TextView? = null
    private var counter: Int? = null
    private var typeid: Int? = null
    private var ownerid: Int? = null
    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {

        val root = inflater.inflate(R.layout.fragment_home, container, false)

        //for the session values
        sessionManager = SessionManager(requireContext())
        sessionManager!!.checkLogin()
        val user = sessionManager!!.userDetails
        userid = user[SessionManager.USER_ID]!!.toInt()

        /*
        //random integer
        int m = (int) ((new Date().getTime() / 1000L) % Integer.MAX_VALUE);
        */

        //handler
        handler = Handler()
        btnSearch = root.findViewById(R.id.btnSearch)

//        sendNotification("Hello World", "Its the end of the world")

        //load price from spinner
        val start = resources.getStringArray(R.array.Start)
        val spinner1 = root.findViewById<Spinner>(R.id.spinner1)
        if (spinner1 != null) {
//            val adapter = context?.let { ArrayAdapter(it,android.R.layout.simple_spinner_item, types) }
//            spinner3.adapter = adapter
//            spinner3.onItemSelectedListener = object :
//                AdapterView.OnItemSelectedListener {
//                override fun onItemSelected(parent: AdapterView<*>,
//                                            view: View, position: Int, id: Long) {
//                    Toast.makeText(context,
//                        getString(R.string.selected_type) + " " +
//                                "" + types[position], Toast.LENGTH_SHORT).show()
//                }
//
//                override fun onNothingSelected(parent: AdapterView<*>) {
//                    // write code to perform some action
//                }
//            }
            // initialize an array adapter for spinner
            val adapter: ArrayAdapter<String> = object : ArrayAdapter<String>(
                this!!.context!!,
                android.R.layout.simple_spinner_dropdown_item,
                start
            ) {
                override fun getDropDownView(
                    position: Int,
                    convertView: View?,
                    parent: ViewGroup
                ): View {
                    val view: TextView = super.getDropDownView(
                        position,
                        convertView,
                        parent
                    ) as TextView

                    // set item text bold and sans serif font
                    view.setTypeface(Typeface.SANS_SERIF, Typeface.BOLD)

                    if (position == 0) {
                        // set the spinner disabled item text color
                        view.setTextColor(Color.LTGRAY)
                    }

                    // set selected item style
                    if (position == spinner1.selectedItemPosition) {
                        view.background = ColorDrawable(Color.parseColor("#F5F5F5"))
                    }

                    return view
                }

                override fun isEnabled(position: Int): Boolean {
                    // disable the third item of spinner
                    return position != 0
                }
            }

            // finally, data bind spinner with adapter
            spinner1.adapter = adapter
        }

        //load price to spinner
        val end = resources.getStringArray(R.array.End)
        val spinner2 = root.findViewById<Spinner>(R.id.spinner2)
        if (spinner2 != null) {
            // initialize an array adapter for spinner
            val adapter: ArrayAdapter<String> = object : ArrayAdapter<String>(
                this!!.context!!,
                android.R.layout.simple_spinner_dropdown_item,
                end
            ) {
                override fun getDropDownView(
                    position: Int,
                    convertView: View?,
                    parent: ViewGroup
                ): View {
                    val view: TextView = super.getDropDownView(
                        position,
                        convertView,
                        parent
                    ) as TextView

                    // set item text bold and sans serif font
                    view.setTypeface(Typeface.SANS_SERIF, Typeface.BOLD)

                    if (position == 0) {
                        // set the spinner disabled item text color
                        view.setTextColor(Color.LTGRAY)
                    }

                    // set selected item style
                    if (position == spinner2.selectedItemPosition) {
                        view.background = ColorDrawable(Color.parseColor("#F5F5F5"))
                    }

                    return view
                }

                override fun isEnabled(position: Int): Boolean {
                    // disable the third item of spinner
                    return position != 0
                }
            }

            // finally, data bind spinner with adapter
            spinner2.adapter = adapter
        }

        //load type spinner
        val types = resources.getStringArray(R.array.Types)
        val spinner3 = root.findViewById<Spinner>(R.id.spinner3)
        val spinner4 = root.findViewById<Spinner>(R.id.spinner4)
        if (spinner3 != null) {
            // initialize an array adapter for spinner
            val adapter: ArrayAdapter<String> = object : ArrayAdapter<String>(
                this!!.context!!,
                android.R.layout.simple_spinner_dropdown_item,
                types
            ) {
                override fun getDropDownView(
                    position: Int,
                    convertView: View?,
                    parent: ViewGroup
                ): View {
                    val view: TextView = super.getDropDownView(
                        position,
                        convertView,
                        parent
                    ) as TextView

                    // set item text bold and sans serif font
                    view.setTypeface(Typeface.SANS_SERIF, Typeface.BOLD)

                    if (position == 0) {
                        // set the spinner disabled item text color
                        view.setTextColor(Color.LTGRAY)
                    }

                    // set selected item style
                    if (position == spinner3.selectedItemPosition) {
                        view.background = ColorDrawable(Color.parseColor("#F5F5F5"))
                    }

                    return view
                }

                override fun isEnabled(position: Int): Boolean {
                    // disable the third item of spinner
                    return position != 0
                }
            }

            // finally, data bind spinner with adapter
            spinner3.adapter = adapter
        }

        //spinner1 event
        spinner1.onItemSelectedListener = object : OnItemSelectedListener {
            override fun onItemSelected(
                adapterView: AdapterView<*>?,
                view: View?,
                i: Int,
                l: Long
            ) {
                if (i > 0) {
                    priceFrom = adapterView!!.getItemAtPosition(i).toString()
                    flag = false
                } else {
                    val tv = view as TextView?
                    tv?.setTextColor(Color.GRAY)
                    flag = true
                }
            }

            override fun onNothingSelected(adapterView: AdapterView<*>?) {}
        }

        //spinner2 event
        spinner2.onItemSelectedListener = object : OnItemSelectedListener {
            override fun onItemSelected(
                adapterView: AdapterView<*>?,
                view: View?,
                i: Int,
                l: Long
            ) {
                if (i > 0) {
                    priceTo = adapterView!!.getItemAtPosition(i).toString()
                    flag2 = false
                } else {
                    val tv = view as TextView?
                    tv?.setTextColor(Color.GRAY)
                    flag2 = true
                }
            }

            override fun onNothingSelected(adapterView: AdapterView<*>?) {}
        }

        //spinner3 event
        spinner3.onItemSelectedListener = object : OnItemSelectedListener {
            override fun onItemSelected(
                adapterView: AdapterView<*>?,
                view: View?,
                i: Int,
                l: Long
            ) {
                type = adapterView!!.getItemAtPosition(i).toString()
                if (i > 0) {
                    flag3 = false

                    if (type.equals("House")) {
                        val houses = resources.getStringArray(R.array.house_array)
                        val adapter: ArrayAdapter<String> = object : ArrayAdapter<String>(
                            requireContext(),
                            android.R.layout.simple_spinner_dropdown_item,
                            houses
                        ) {
                            override fun getDropDownView(
                                position: Int,
                                convertView: View?,
                                parent: ViewGroup
                            ): View {
                                val view: TextView = super.getDropDownView(
                                    position,
                                    convertView,
                                    parent
                                ) as TextView

                                // set item text bold and sans serif font
                                view.setTypeface(Typeface.SANS_SERIF, Typeface.BOLD)

                                if (position == 0) {
                                    // set the spinner disabled item text color
                                    view.setTextColor(Color.LTGRAY)
                                }

                                // set selected item style
                                if (position == spinner3.selectedItemPosition) {
                                    view.background = ColorDrawable(Color.parseColor("#F5F5F5"))
                                }

                                return view
                            }

                            override fun isEnabled(position: Int): Boolean {
                                // disable the third item of spinner
                                return position != 0
                            }
                        }

                        // finally, data bind spinner with adapter
                        spinner4.adapter = adapter
                    } else if (type.equals("Room")) {
                        val rooms = resources.getStringArray(R.array.room_array)
                        val adapter: ArrayAdapter<String> = object : ArrayAdapter<String>(
                            requireContext(),
                            android.R.layout.simple_spinner_dropdown_item,
                            rooms
                        ) {
                            override fun getDropDownView(
                                position: Int,
                                convertView: View?,
                                parent: ViewGroup
                            ): View {
                                val view: TextView = super.getDropDownView(
                                    position,
                                    convertView,
                                    parent
                                ) as TextView

                                // set item text bold and sans serif font
                                view.setTypeface(Typeface.SANS_SERIF, Typeface.BOLD)

                                if (position == 0) {
                                    // set the spinner disabled item text color
                                    view.setTextColor(Color.LTGRAY)
                                }

                                // set selected item style
                                if (position == spinner3.selectedItemPosition) {
                                    view.background = ColorDrawable(Color.parseColor("#F5F5F5"))
                                }

                                return view
                            }

                            override fun isEnabled(position: Int): Boolean {
                                // disable the third item of spinner
                                return position != 0
                            }
                        }

                        // finally, data bind spinner with adapter
                        spinner4.adapter = adapter
                    } else if (type.equals("Hotel")) {
                        val hotels = resources.getStringArray(R.array.hotel_array)
                        val adapter: ArrayAdapter<String> = object : ArrayAdapter<String>(
                            requireContext(),
                            android.R.layout.simple_spinner_dropdown_item,
                            hotels
                        ) {
                            override fun getDropDownView(
                                position: Int,
                                convertView: View?,
                                parent: ViewGroup
                            ): View {
                                val view: TextView = super.getDropDownView(
                                    position,
                                    convertView,
                                    parent
                                ) as TextView

                                // set item text bold and sans serif font
                                view.setTypeface(Typeface.SANS_SERIF, Typeface.BOLD)

                                if (position == 0) {
                                    // set the spinner disabled item text color
                                    view.setTextColor(Color.LTGRAY)
                                }

                                // set selected item style
                                if (position == spinner3.selectedItemPosition) {
                                    view.background = ColorDrawable(Color.parseColor("#F5F5F5"))
                                }

                                return view
                            }

                            override fun isEnabled(position: Int): Boolean {
                                // disable the third item of spinner
                                return position != 0
                            }
                        }

                        // finally, data bind spinner with adapter
                        spinner4.adapter = adapter
                    } else if (type.equals("Commercial Building")) {
                        val commercials = resources.getStringArray(R.array.commercial_array)
                        val adapter: ArrayAdapter<String> = object : ArrayAdapter<String>(
                            requireContext(),
                            android.R.layout.simple_spinner_dropdown_item,
                            commercials
                        ) {
                            override fun getDropDownView(
                                position: Int,
                                convertView: View?,
                                parent: ViewGroup
                            ): View {
                                val view: TextView = super.getDropDownView(
                                    position,
                                    convertView,
                                    parent
                                ) as TextView

                                // set item text bold and sans serif font
                                view.setTypeface(Typeface.SANS_SERIF, Typeface.BOLD)

                                if (position == 0) {
                                    // set the spinner disabled item text color
                                    view.setTextColor(Color.LTGRAY)
                                }

                                // set selected item style
                                if (position == spinner3.selectedItemPosition) {
                                    view.background = ColorDrawable(Color.parseColor("#F5F5F5"))
                                }

                                return view
                            }

                            override fun isEnabled(position: Int): Boolean {
                                // disable the third item of spinner
                                return position != 0
                            }
                        }

                        // finally, data bind spinner with adapter
                        spinner4.adapter = adapter
                    } else {
                        val events = resources.getStringArray(R.array.event_array)
                        val adapter: ArrayAdapter<String> = object : ArrayAdapter<String>(
                            requireContext(),
                            android.R.layout.simple_spinner_dropdown_item,
                            events
                        ) {
                            override fun getDropDownView(
                                position: Int,
                                convertView: View?,
                                parent: ViewGroup
                            ): View {
                                val view: TextView = super.getDropDownView(
                                    position,
                                    convertView,
                                    parent
                                ) as TextView

                                // set item text bold and sans serif font
                                view.setTypeface(Typeface.SANS_SERIF, Typeface.BOLD)

                                if (position == 0) {
                                    // set the spinner disabled item text color
                                    view.setTextColor(Color.LTGRAY)
                                }

                                // set selected item style
                                if (position == spinner3.selectedItemPosition) {
                                    view.background = ColorDrawable(Color.parseColor("#F5F5F5"))
                                }

                                return view
                            }

                            override fun isEnabled(position: Int): Boolean {
                                // disable the third item of spinner
                                return position != 0
                            }
                        }

                        // finally, data bind spinner with adapter
                        spinner4.adapter = adapter
                    }
                } else {
                    val tv = view as TextView?
                    tv?.setTextColor(Color.GRAY)
                    flag3 = true
                }
            }

            override fun onNothingSelected(adapterView: AdapterView<*>?) {}
        }

        //spinner4 event
        spinner4.onItemSelectedListener = object : OnItemSelectedListener {
            override fun onItemSelected(
                adapterView: AdapterView<*>?,
                view: View?,
                i: Int,
                l: Long
            ) {
                if (spinner4.count == 0) {
                    flag4 = true
                } else {
                    flag4 = false
                    category = adapterView!!.getItemAtPosition(i).toString()
                }
            }

            override fun onNothingSelected(adapterView: AdapterView<*>?) {}
        }


        btnSearch!!.setOnClickListener {
            if (!validateSpinner1() or !validateSpinner2() or !validateSpinner3() or !validateSpinner4()) {
                return@setOnClickListener
            }
            val intent =
                Intent(context, SpaceActivity::class.java).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK)
            intent.putExtra(FROM, priceFrom)
            intent.putExtra(TO, priceTo)
            intent.putExtra(TYPE, type)
            intent.putExtra(CATEGORY, category)
            context!!.startActivity(intent)
            spinner1.setSelection(0)
            spinner2.setSelection(0)
            spinner3.setSelection(0)
            spinner4.setSelection(0)
        }

        //notifications
        content()
//        pushNotifications()

        return root
    }

    override fun onCreateOptionsMenu(menu: Menu, inflater: MenuInflater) {
        inflater.inflate(R.menu.home, menu)
        val menuItem = menu.findItem(R.id.notification)
        val actionView: View = MenuItemCompat.getActionView(menuItem)
        tvNotificationBadge = actionView.findViewById(R.id.notification_badge)
        actionView.setOnClickListener {
            onOptionsItemSelected(menuItem)
        }
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            R.id.notification -> {
//             Toast.makeText(context, "Selected!", Toast.LENGTH_SHORT).show()
                val intent = Intent(context, NotificationsActivity::class.java)
                startActivity(intent)
                updateNotifications2()
                //update method here
            }
        }
        return super.onOptionsItemSelected(item)
    }

    private fun pushNotifications() {
        val url = "http://192.168.137.1:8080/osf/mobile/pushnotifications.php"
        // url = "http://192.168.43.44:8080/IRO/Android/register.php"
        val stringRequest = object : StringRequest(
            Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Notification: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    if (success == "1") {
                        val jsonArray = jsonObject.getJSONArray("data")
                        for (i in 0 until jsonArray.length()) {
                            val obj = jsonArray.getJSONObject(i)
                            typeid = obj.getInt("type")
                            subject = obj.getString("subject")
                            content = obj.getString("content")
                            sendNotification(typeid, subject, content)
                        }
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(requireContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT)
                        .show()
                }
            },
            Response.ErrorListener { error ->
                error.printStackTrace()
                Toast.makeText(requireContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT)
                    .show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["userid"] = userid.toString()

                return params

            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }


    fun validateSpinner1(): Boolean {
        if (flag!!) {
            (spinner1.selectedView as TextView).error = "This is required"
            return false
        } else {
            return true
        }
    }

    fun validateSpinner2(): Boolean {
        if (flag2!!) {
            (spinner2.selectedView as TextView).error = "This is required"
            return false
        } else {
            return true
        }
    }

    fun validateSpinner3(): Boolean {
        if (flag3!!) {
            (spinner3.selectedView as TextView).error = "This is required"
            return false
        } else {
            return true
        }
    }

    fun validateSpinner4(): Boolean {
        if (flag4!!) {
            (spinner4.selectedView as TextView).error = "This is required"
            return false
        } else {
            return true
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setHasOptionsMenu(true)
    }

//    override fun onCreateOptionsMenu(menu: Menu, inflater: MenuInflater) {
//        inflater.inflate(R.menu.home, menu)
//    }

    fun content() {
        pushNotifications()
        countNotifications()
        setupBadge()
        updateNotifications()
        //checkStatus(user_id);
        refresh(1000 * 1)
    }

    private fun refresh(i: Int) {
        val runnable = Runnable { //pushNotification();
            //updateNotification(user_id, String.valueOf(notification_type_id));
            content()
        }
        handler!!.postDelayed(runnable, i.toLong())
    }

    override fun onDestroy() {
        super.onDestroy()
        handler!!.removeCallbacksAndMessages(null)
        handler = null
    }

    fun sendNotification(
        typeid: Int?,
        subject: String?,
        content: String?
    ) {
        if(typeid==1) {
            val intent = Intent(context, NotificationsActivity::class.java).apply {
                flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
            }
            val pendingIntent: PendingIntent = PendingIntent.getActivity(context, 0, intent, 0)
            val bitmap = BitmapFactory.decodeResource(
                context!!.resources,
                R.drawable.ic_online_space_finder_round
            )

            val builder = NotificationCompat.Builder(requireContext(), CHANNEL_1_ID)
                .setContentTitle(subject)
                .setContentText(content)
                .setSmallIcon(R.drawable.ic_online_space_finder_round)
//             .setLargeIcon(bitmap)
//             .setStyle(NotificationCompat.BigPictureStyle().bigPicture(bitmap))
                .setContentIntent(pendingIntent)
                .setPriority(NotificationCompat.PRIORITY_DEFAULT)

            with(NotificationManagerCompat.from(requireContext())) {
                notify(1, builder.build())
            }
        } else if(typeid==2) {
            val intent = Intent(context,  NotificationsActivity::class.java).apply {
                flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
            }
            val pendingIntent: PendingIntent = PendingIntent.getActivity(context, 0, intent, 0)
            val bitmap = BitmapFactory.decodeResource(
                context!!.resources,
                R.drawable.ic_online_space_finder_round
            )

            val builder = NotificationCompat.Builder(requireContext(), CHANNEL_1_ID)
                .setContentTitle(subject)
                .setContentText(content)
                .setSmallIcon(R.drawable.ic_online_space_finder_round)
//             .setLargeIcon(bitmap)
//             .setStyle(NotificationCompat.BigPictureStyle().bigPicture(bitmap))
                .setContentIntent(pendingIntent)
                .setPriority(NotificationCompat.PRIORITY_DEFAULT)

            with(NotificationManagerCompat.from(requireContext())) {
                notify(2, builder.build())
            }
        } else {
            val intent = Intent(context, NotificationsActivity::class.java).apply {
                flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
            }
            val pendingIntent: PendingIntent = PendingIntent.getActivity(context, 0, intent, 0)
            val bitmap = BitmapFactory.decodeResource(
                context!!.resources,
                R.drawable.ic_online_space_finder_round
            )

            val builder = NotificationCompat.Builder(requireContext(), CHANNEL_1_ID)
                .setContentTitle(subject)
                .setContentText(content)
                .setSmallIcon(R.drawable.ic_online_space_finder_round)
//             .setLargeIcon(bitmap)
//             .setStyle(NotificationCompat.BigPictureStyle().bigPicture(bitmap))
                .setContentIntent(pendingIntent)
                .setPriority(NotificationCompat.PRIORITY_DEFAULT)

            with(NotificationManagerCompat.from(requireContext())) {
                notify(3, builder.build())
            }
        }
    }

    private fun updateNotifications() {
        val url = "http://192.168.137.1:8080/osf/mobile/updatenotifications.php"
        // url = "http://192.168.43.44:8080/IRO/Android/register.php"
        val stringRequest = object : StringRequest(
            Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Update Notification: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    if (success == "1") {
                        Log.e("Success Notification: ",success)
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(requireContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT)
                        .show()
                }
            },
            Response.ErrorListener { error ->
                error.printStackTrace()
                Toast.makeText(requireContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT)
                    .show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["userid"] = userid.toString()

                return params

            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }

    private fun updateNotifications2() {
        val url = "http://192.168.137.1:8080/osf/mobile/updatenotifications2.php"
        // url = "http://192.168.43.44:8080/IRO/Android/register.php"
        val stringRequest = object : StringRequest(
            Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Update Notification2: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    if (success == "1") {
                        Log.e("Success Notification2: ",success)
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(requireContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT)
                        .show()
                }
            },
            Response.ErrorListener { error ->
                error.printStackTrace()
                Toast.makeText(requireContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT)
                    .show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["userid"] = userid.toString()

                return params

            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }

    private fun countNotifications() {
        val url = "http://192.168.137.1:8080/osf/mobile/countnotifications.php"
        // url = "http://192.168.43.44:8080/IRO/Android/register.php"
        val stringRequest = object : StringRequest(
            Method.POST, url,
            Response.Listener { response ->
                try {
                    Log.e("Count Notification: ", response)
                    val jsonObject = JSONObject(response)
                    val success = jsonObject.getString("success")
                    if (success == "1") {
                        counter = jsonObject.getInt("counter")
                    } else {
                        counter = 0
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(requireContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT)
                        .show()
                }
            },
            Response.ErrorListener { error ->
                error.printStackTrace()
                Toast.makeText(requireContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT)
                    .show()
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["userid"] = userid.toString()

                return params

            }
        }
        AppController.getmInstance()!!.addToRequestQueue(stringRequest)
    }

    private fun setupBadge() {
        if (tvNotificationBadge != null) {
            if (counter == 0) {
                if (tvNotificationBadge!!.visibility != View.GONE) {
                    tvNotificationBadge!!.visibility = View.GONE
                }
            } else {
                tvNotificationBadge!!.setText(Math.min(counter!!, 99).toString())
                if (tvNotificationBadge!!.visibility != View.VISIBLE) {
                    tvNotificationBadge!!.visibility = View.VISIBLE
                }
            }
        }
    }

    companion object {
        const val FROM = "com.example.onlinespacefinder.FROM"
        const val TO = "com.example.onlinespacefinder.START"
        const val TYPE = "com.example.onlinespacefinder.TYPE"
        const val CATEGORY = "com.example.onlinespacefinder.CATEGORY"
    }
}
