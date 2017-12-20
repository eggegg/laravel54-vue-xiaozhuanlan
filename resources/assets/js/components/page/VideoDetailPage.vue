<template>
    <div class="container">
        <div class="">
            <!-- Main content -->
            <div class="" style="width:660px;margin: 0 auto;overflow: hidden;">
                <div class="video-player">
                    <div class="video-card">
                        <img class="img-responsive" :src="videoThumb(video.thumbnail)" alt="">
                    </div>
                </div>
                <!-- End Video player -->

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="video-desc">
                            <h3>{{ video.title }}</h3>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="media">
                                        <div class="media-left media-middle">
                                            <router-link :to="{ name: 'ChannelPage', params: {id: video.channel_id, slug: $root.slug(video.channel.name)}}">
                                                <img class="media-object" :src="video.channel.logo" alt="avatar">
                                            </router-link>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                <router-link :to="{ name: 'ChannelPage', params: {id: video.channel_id, slug: $root.slug(video.channel.name)}}">
                                                    {{ video.channel.name }}
                                                </router-link>{{ video.created_at }}</h4>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                                <button class="btn btn-subscribe btn-primary"><span class="glyphicon glyphicon-play"></span> Subscribe</button>
                                                <button disabled class="btn btn-default">56,454</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right view-count">
                                    <h3>{{ video.views }} view{{ video.views > 1 ? 's' : '' }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body video-footer">
                        <div class="row">
                            <div class="col-md-8">
                                <a href=""> <span class="glyphicon glyphicon-plus"></span> Add to </a>
                                <a href=""> <span class="glyphicon glyphicon-share"></span> Share </a>
                                <a href=""> <span class="glyphicon glyphicon-option-horizontal"></span> More </a>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href=""> <span class="glyphicon glyphicon-thumbs-up"></span> 32 </a>
                                <a href=""> <span class="glyphicon glyphicon-thumbs-down"></span> 12 </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Video Details -->

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h5 class="text-dark">Published on {{ video.updated_at }}</h5>

                        <!--<div class="desc-text"><p>{{ video.description }}</p></div>-->
                        <div class="desc-text">
                            <div class="markdown" v-html="video.description_html"></div>
                        </div>
                        <h5 class="text-dark">Category <a href="">{{ video.category.name }}</a></h5>
                    </div>
                </div>
                <!-- End Video Description -->

                <div class="panel panel-default">
                    <div class="panel-heading comment-box">Comment</div>

                    <div class="panel-body">
                        <div class="media" v-if="canComment()">
                            <div class="media-left">
                                <a href="#">
                                    <img width="48" class="media-object" :src="$root.auth.avatar" :alt="$root.auth.name">
                                </a>
                            </div>
                            <div class="media-body">
                                <form @submit.prevent="saveComment()" method="post" action="">
                                    <div class="form-group">
                                        <textarea required name="comment" v-model="newComment" class="form-control" :placeholder="'Commenting as ' + $root.auth.name"></textarea>
                                    </div>
                                    <div class="text-right">
                                        <button type="reset" @click="newComment = null" :disabled="!newComment || commenting" class="btn btn-sm btn-default">Cancel</button>
                                        <button type="submit" :disabled="!newComment || commenting" class="btn btn-sm btn-info">
                                            <span v-show="commenting" class="glyphicon glyphicon-refresh spin"></span> Comment
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <!-- End Comment form -->

                        <div class="text-center" v-if="!canComment()">
                            Please <a class="btn btn-xs btn-primary" href="/login">Login</a> to post comment.
                        </div>

                        <hr>

                        <div class="comment-thread">
                            <div class="discussions">
                                <div class="text-center" v-show="loading">
                                    <span class="glyphicon glyphicon-refresh spin"></span>
                                </div>

                                <h5 v-show="comments.data.length">COMMENTS • {{ comments.data.length }}</h5>

                                <div class="media" v-for="(comment, index) in comments.data">
                                    <div class="media-left media-top">
                                        <a href="">
                                            <img width="48" class="media-object" :src="comment.user.avatar" :alt="comment.user.name">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="dropdown pull-right">
                                            <button class="btn btn-sm btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <span class="glyphicon glyphicon-option-vertical"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">

                                                <li v-if="$root.auth && $root.auth.id == comment.user_id"><a @click.prevent="deleteComment(index)" href="#">
                                                    <span v-show="!commenting" class="glyphicon glyphicon-trash text-danger"></span> Delete</a>
                                                </li>
                                                <!--<li v-if="$root.auth && $root.auth.id == comment.user_id"><a href="#"> <span class="glyphicon glyphicon-pencil"></span> Edit</a></li>-->
                                                <li><a href="#"> <span class="glyphicon glyphicon-flag"></span> Report It</a></li>
                                            </ul>
                                        </div>
                                        <h4 class="media-heading"><a href="">{{ comment.user.name }}</a> <small>{{ comment.created_at }}</small></h4>
                                        <p class="desc-text">{{ comment.body }}﻿</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Comment thread -->
                    </div>

                </div>
                <!-- End Comment -->
            </div>
            <!-- End Main Content -->

        </div>
    </div>
</template>

<script>
    import { markdownEditor } from 'vue-simplemde'

    export default {
        components:{
            markdownEditor
        },
        props: ['id', 'slug'],
        data() {
          return {
              video: {
                  channel: {
                      id: 1,
                      logo: '/img/avatar-placeholder.jpg'
                  },
                  category: {

                  }
              },
              comments: {
                  data: []
              },
              newComment: null,
              loading: false,
              commenting: false,
              youtubeId: false
          }
        },
        watch: {
            '$route' (to, from) {
                this.getVideo();
                this.fetchComments();
            }
        },

        mounted() {
            this.getVideo();
            this.fetchComments();
            console.log('Video Details Component mounted.', this.id);
        },

        methods: {
            getVideo() {
                this.$Progress.start();
                axios.get('/api/posts/' + this.id).then((res) => {
                    this.$Progress.finish();
                    this.video = res.data;
                    this.video.description_html = this.parse(this.video.description)
                    // change the title of page
                    window.document.title = this.video.title;
                }).catch((err) => {
                    this.$Progress.finish();
                });
            },
            parse(html) {
                return marked(html)
            },
            videoThumb(thumb) {
              return "http://lorempixel.com/660/366/?" + this.video.id
            },

            canComment() {
                return Laravel.hasOwnProperty('Auth')
            },

            fetchComments(url) {
                url = url || '/api/comments?post_id=' + this.id;

                this.loading = true;
                axios.get( url ).then((res) => {
                    this.loading = false;
                    this.comments = res.data;
                }).catch((err) => {
                    this.loading = false;
                });
            },

            saveComment() {
                let vm = this;
                    vm.commenting = true;

                axios.post('/api/comments', {
                    body: this.newComment,
                    post_id: this.video.id
                }).then(function (res) {
                    vm.newComment = '';
                    vm.comments.data.unshift(res.data);
                    vm.commenting = false;
                }).catch(function (error) {
                    console.log(error);
                    vm.commenting = false;
                });
            },

            deleteComment(index) {
                let vm = this;
                let comment = vm.comments.data[index];

                if( window.confirm('Are sure want to delete this comment?')) {
                    vm.$Progress.start();
                    axios.delete('/api/comments/' + comment.id).then(function (res) {
                        vm.comments.data.splice(index, 1);
                        vm.$Progress.finish();
                    }).catch(function (error) {
                        console.log(error);
                        vm.$Progress.finish();
                    });
                }
            }
        }
    }
</script>