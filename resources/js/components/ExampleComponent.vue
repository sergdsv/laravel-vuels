<template>

        <table id="" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Название</th>
                  <th>Категория</th>
                  <th>Теги</th>
                  <th>Картинка</th>
                  <th>Действия</th>
                </tr>
                </thead>
                <tbody>

                    <tr v-for="post in result.posts">
                      <td>{{post.id}}</td>
                      <td>{{post.title}}</td>
                      <td>{{post.category.title}}</td>
                      <td>
                        <span v-for="(tags, index) in post.tags">
                            <span v-if="index != 0">, </span>
                            <span>{{ tags.title }}</span>
                        </span>
                        </td>
                      <td><img :src="getImgUrl(post.image)" width="100" /></td>
                      <td></td>
                    </tr>
                </tbody>
              </table>


</template>

<script>
    export default {

        data: function(){
            return{
                result: []
            }
        },

        mounted(){
           this.getJson();
        },

        methods: {
            getImgUrl: function (pic) {
                return '/uploads/'+pic
            },

            getJson(){
                 axios
                    .get('/indexjson')
                    .then(response => (this.result = response.data))
                    .catch(error => console.log(error))
            },

            getTags(){}

        },
    }
</script>
