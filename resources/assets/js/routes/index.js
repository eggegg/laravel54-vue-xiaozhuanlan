import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);


import HomePage from '../components/page/HomePage.vue'
import TrendingPage from '../components/page/TrendingPage.vue'
import SubscriptionPage from '../components/page/SubscriptionPage.vue'
import ChannelPage from '../components/page/ChannelPage.vue'
import VideoDetailPage from '../components/page/VideoDetailPage.vue'

export default new Router({
    // mode: 'history', // to enable html5 history api
    routes: [
        {
            path: '/',
            name: 'HomePage',
            component: HomePage
        },
        {
            path: '/trending',
            name: 'TrendingPage',
            component: TrendingPage
        },
        {
            path: '/subscriptions',
            name: 'SubscriptionPage',
            component: SubscriptionPage
        },
        {
            path: '/channel/:id/:slug',
            name: 'ChannelPage',
            component: ChannelPage,
            props: true
        },
        {
            path: '/video/:id/:slug',
            name: 'VideoDetailPage',
            component: VideoDetailPage,
            props: true
        }
    ]
});