<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'createPost'}" class="btn btn-success">Create new company</router-link>
        </div>

        <div class="panel panel-default">
            
            <div class="panel-heading">Companies list</div>
            <div class="panel-body">

                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Теги</th>
                        <th>Картинка</th>
                        <th width="100">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="post, index in posts">
                        <td>{{ post.id }}</td>
                        <td>{{ post.title }}</td>
                        <td>{{ post.category.title}}</td>
                        <td>
                            <span v-for="(tags, index) in post.tags">
                                <span v-if="index != 0">, </span>
                                <span>{{ tags.title }}</span>
                            </span>
                        </td>
                        <td><img :src="getImgUrl(post.image)" width="100" /></td>
                        <td>
                            <router-link :to="{name: 'editPost', params: {id: post.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <a href="#"
                               class="btn btn-xs btn-danger"
                               v-on:click="deleteEntry(post.id, index)">
                                Delete
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                posts: []
            }
        },
        mounted() {
            var app = this;
            axios.get('/api/admin/vposts')
                .then(function (resp) {
                    app.posts = resp.data.posts;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load posts");
                });
        },
        methods: {
            deleteEntry(id, index) {
                if (confirm("Do you really want to delete it?")) {
                    var app = this;
                    axios.delete('api/admin/vposts/' + id)
                        .then(function (resp) {
                            app.posts.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete company");
                        });
                }
            },
            getImgUrl: function (pic) {
                return '/uploads/'+pic
            },
        }
    }
</script>