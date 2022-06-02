package com.example.onlinespacefinder

import android.app.Application
import android.app.NotificationChannel
import android.app.NotificationManager
import android.os.Build
import android.text.TextUtils
import com.android.volley.DefaultRetryPolicy
import com.android.volley.Request
import com.android.volley.RequestQueue
import com.android.volley.toolbox.ImageLoader
import com.android.volley.toolbox.Volley

class AppController: Application() {
    private var mRequestQueue: RequestQueue? = null
    private var mImageLoader: ImageLoader? = null

    override fun onCreate() {
        super.onCreate()
        mInstance = this
        createNotificationChannels()
    }

    private fun createNotificationChannels() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val channel = NotificationChannel(
                CHANNEL_1_ID,
                "Channel 1",
                NotificationManager.IMPORTANCE_HIGH
            )
            channel.description = "This is Channel 1"

            val channe2 = NotificationChannel(
                CHANNEL_2_ID,
                "Channel 2",
                NotificationManager.IMPORTANCE_LOW
            )
            channel.description = "This is Channel 2"

            val manager = getSystemService<NotificationManager>(NotificationManager::class.java)
            manager!!.createNotificationChannel(channel)
            manager.createNotificationChannel(channe2)
        }
    }

    fun getmRequestQueue(): RequestQueue? {
        if (mRequestQueue == null) {
            mRequestQueue = Volley.newRequestQueue(applicationContext)
        }
        return mRequestQueue
    }

//    fun getmImageLoader(): ImageLoader {
//        getmRequestQueue()
//        if (mImageLoader == null) {
//            mImageLoader = ImageLoader(this.mRequestQueue, BitmapCache())
//        }
//        return this.mImageLoader!!
//    }

    fun <T> addToRequestQueue(
        request: Request<T>,
        tag: String?
    ) {
        request.retryPolicy = DefaultRetryPolicy(
            0,
            DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
            DefaultRetryPolicy.DEFAULT_BACKOFF_MULT
        )
        request.tag = if (TextUtils.isEmpty(tag)) TAG else tag
        getmRequestQueue()!!.add(request)

        //request.setTag((TextUtils.isEmpty(tag) ? TAG :tag));
        //getmRequestQueue().add(request);
    }

    fun <T> addToRequestQueue(request: Request<T>) {
        request.retryPolicy = DefaultRetryPolicy(
            0,
            DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
            DefaultRetryPolicy.DEFAULT_BACKOFF_MULT
        )
        request.tag = TAG
        getmRequestQueue()!!.add(request)

        //request.setTag(TAG);
        //getmRequestQueue().add(request);
    }

    fun cancelPendingRequest(tag: Any) {
        if (mRequestQueue != null) {
            mRequestQueue!!.cancelAll(tag)
        }
    }

    companion object {
        val CHANNEL_1_ID = "channel1"
        val CHANNEL_2_ID = "channel2"
        private var mInstance: AppController? = null
        val TAG = AppController::class.java.getSimpleName()


        @Synchronized
        fun getmInstance(): AppController? {
            return mInstance
        }
    }
}