import { RouteRecordRaw } from 'vue-router'
import { Component } from 'vue'

const pagination = (Component: Component, parentName: string): RouteRecordRaw => {
  return {
    path: 'page/:pagenumber(\\d+)?',
    component: Component,
    name: `${parentName}Paged`,
  }
}

export default pagination
