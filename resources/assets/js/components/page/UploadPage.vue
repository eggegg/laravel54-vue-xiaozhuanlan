<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading">发布文章</div>

                    <div class="panel-body">

                        <p class="alert text-center alert-warning"></p>

                        <div class="row">
                            <div class="col-md-12">
                                <form method="post" @submit.prevent="uploadVideo()" class="form-horizontal">

                                    <div class="form-group">
                                        <label for="category" class="col-sm-2 control-label">Category</label>
                                        <div class="col-sm-10">
                                            <select v-model="video.category_id" required name="category" class="form-control" id="category">
                                                <option :value="cat.id" v-for="cat in categories">{{ cat.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="url" class="col-sm-2 control-label">Video URL</label>
                                        <div class="col-sm-10">
                                            <input v-model="video.url"  required type="url" class="form-control" id="url" placeholder="YouTube video url">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-10">
                                            <input v-model="video.title" required type="text" class="form-control" id="title" placeholder="Title of Video">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="desc" class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10">
                                            <markdown-editor v-model="video.description" ref="markdownEditor"></markdown-editor>
                                            <!--<textarea v-model="video.description" minlength="10" required class="form-control" id="desc" placeholder="Description for video"></textarea>-->
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button :disabled="loading" type="submit" class="btn btn-info">
                                                <span v-show="loading" class="glyphicon glyphicon-refresh spin"></span> Upload
                                            </button>
                                            <button type="reset" :disabled="loading" class="btn btn-default">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- End Video thumb preview -->
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { markdownEditor } from 'vue-simplemde'

    export default {
        components: {
            markdownEditor
        },
        data() {
            return {
                image:'',
                video: {},
                categories: [],
                loading: false,
                videoThumb: 'https://lorempixel.com/640/480/?68687',
                configs: {
                    spellChecker: false // 禁用拼写检查
                }
            }
        },

        mounted() {
            this.getCategories();
            console.log('Upload Component mounted.')
        },

        methods: {
            getCategories() {
                this.$Progress.start();
                // change the title of page
                window.document.title = 'Upload a Video on QTube';

                axios.get('/api/posts?categories=true').then((res) => {
                    this.$Progress.finish();
                    this.categories = res.data.categories;
                }).catch((err) => {
                    this.$Progress.finish();
                    console.log(err);
                });
            },

            uploadVideo() {
                let vm = this;
                vm.loading = true;

                // add other fields
                vm.video.thumbnail = vm.videoThumb;
                vm.video.channel_id = vm.$root.channel.id;

                axios.post('/api/posts', vm.video).then(function (res) {
                    vm.video = {};
                    vm.loading = false;
                    alert('Video has been uploaded, Go to home page to see it.');
                }).catch(function (error) {
                    console.log(error);
                    vm.loading = false;
                });
            },

        }
    }
</script>