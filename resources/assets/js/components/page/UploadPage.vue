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
                                <form method="post" @submit.prevent="uploadPost()" class="form-horizontal">

                                    <div class="form-group">
                                        <label for="category" class="col-sm-2 control-label">Category</label>
                                        <div class="col-sm-10">
                                            <select v-model="Post.category_id" required name="category" class="form-control" id="category">
                                                <option :value="cat.id" v-for="cat in categories">{{ cat.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="url" class="col-sm-2 control-label">Post URL</label>
                                        <div class="col-sm-10">
                                            <input v-model="Post.url"  required type="url" class="form-control" id="url" placeholder="Post url">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-10">
                                            <input v-model="Post.title" required type="text" class="form-control" id="title" placeholder="Title of Post">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="desc" class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10">
                                            <markdown-editor v-model="Post.description" ref="markdownEditor"></markdown-editor>
                                            <!--<textarea v-model="Post.description" minlength="10" required class="form-control" id="desc" placeholder="Description for Post"></textarea>-->
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button :disabled="loading" type="submit" class="btn btn-info">
                                                <span v-show="loading" class="glyphicon glyphicon-refresh spin"></span> Send
                                            </button>
                                            <button type="reset" :disabled="loading" class="btn btn-default">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- End Post thumb preview -->
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
                Post: {},
                categories: [],
                loading: false,
                PostThumb: 'https://lorempixel.com/640/480/?68687',
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
                window.document.title = 'Post a article';

                axios.get('/api/posts?categories=true').then((res) => {
                    this.$Progress.finish();
                    this.categories = res.data.categories;
                }).catch((err) => {
                    this.$Progress.finish();
                    console.log(err);
                });
            },

            uploadPost() {
                let vm = this;
                vm.loading = true;

                // add other fields
                vm.Post.thumbnail = vm.PostThumb;
                vm.Post.channel_id = vm.$root.channel.id;

                axios.post('/api/posts', vm.Post).then(function (res) {
                    vm.Post = {};
                    vm.loading = false;
                    alert('Post has been uploaded, Go to home page to see it.');
                }).catch(function (error) {
                    console.log(error);
                    vm.loading = false;
                });
            },

        }
    }
</script>