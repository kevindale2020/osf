package com.example.onlinespacefinder.ui.home

import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import com.example.onlinespacefinder.Space

class HomeViewModel : ViewModel() {

    private val spaceList: MutableLiveData<List<Space>>? = null

    fun getSpace(): MutableLiveData<List<Space>>? {
        return spaceList
    }

    fun setSpace(space: List<Space>) {
        spaceList!!.value = space
    }
}