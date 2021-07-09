import { RouteRecordRaw } from 'vue-router'
import { Component } from 'vue'

const commentPage = (Component: Component, parentName: string): RouteRecordRaw => {
  return {
    path: 'comment-page-:commentPage(\\d+)?',
    component: Component,
    name: `${parentName}CommentPage`,
  }
}

export default commentPage
