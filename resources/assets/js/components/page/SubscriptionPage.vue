<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p class="alert alert-info text-center">订阅的频道列表</p>
                        <div class="media">
                            <div class="media-left media-middle">
                                <a href="#">
                                    <img width="94" src="/img/avatar-placeholder.jpg" alt="..." class="media-object">
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <a href="">Channel</a>
                                </h4>

                                <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                    <button class="btn btn-subscribe btn-primary"><span class="glyphicon glyphicon-play"></span> UnSubscribe</button>
                                    <button disabled class="btn btn-default">56,454</button>
                                </div>

                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut commodi eveniet illo inventore molestiae neque nihil odio officiis, sunt voluptatum? Aut consequatur labore molestiae possimus quia sapiente veniam! Alias, quibusdam.</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="panel-body">
                        List of Posts from this Channel
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>