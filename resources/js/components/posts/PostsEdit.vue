<template>
<div>
    <div class="form-group">
        <router-link to="/" class="btn btn-default">Back</router-link>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Create new post</div>
        <div class="panel-body">
        <div class="col-md-6">
            <form v-on:submit="saveForm(post.id)">
                <input type="hidden" name="_token" :value="csrf">
                <div class="row">
                    <div class="col-xs-12 form-group">
                        <label class="control-label">Название</label>
                        <input type="text" v-model="post.title" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="file-upload-form">
                        Upload an image file:
                        <input type="file" name="image" id="image" @change="previewImage" accept="image/*">
                    </div>
                    <div class="image-preview" v-if="imageData.length > 0">
                        <img class="preview" :src="imageData">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 form-group">
                            <multiselect
                                v-model="selectedCategory"
                                :options="categories"
                                track-by="id"
                                label="title"
                                >
                            </multiselect>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 form-group">
                            <multiselect
                                v-model="selectedTags"
                                :options="tags"
                                label="title"
                                track-by="title"
                                :multiple="true"
                                >
                            </multiselect>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 form-group">
                      <label>Дата:</label></br>
                        <date-picker v-model="post.date" lang="ru" valueType="format"></date-picker>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Полный текст</label>
                        <textarea v-model="post.content" cols="30" rows="10" class="form-control" ></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 form-group">
                        <button class="btn btn-success">Create</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>

</div>

</template>
<script>

    import Multiselect from 'vue-multiselect'
    import DatePicker from 'vue2-datepicker'
    export default {
        components:
            {
                Multiselect,
                DatePicker
            },
        data: function () {
            return {
                categories: [],
                tags: [],
                selTags: [],
                post: {
                    title: '',
                    category_id: '',
                    tags_id: '',
                    date: '',
                    content: '',
                    image: ''
                },
                imageData: "",
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        success : function(data) {
           console.log(data);
        },

        mounted() {

            this.imageData = this.post.image

            var app = this;
            axios.get('/api/admin/vposts/' + this.$route.params.id + '/edit/')
                .then(function (resp) {
                    console.log(resp.data)
                    app.post = resp.data.post
                    app.categories = resp.data.categories
                    app.tags = resp.data.tags
                    app.selTags = resp.data.selTags

                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load posts");
                });
        },

        computed: {
            selectedCategory: {
                get() {
                   return this.categories.find(category_id => this.post.category_id === category_id.id);
                },
                set(val){
                    this.post.category_id = val.id;
                }
            },

            selectedTags: {
                get() {
                   return this.tags
                   // return this.categories.find(category_id => this.post.category_id === category_id.id);
                },
                set(val){
                    // this.post.category_id = val.id;
                    this.post.tags_id = val.map(tags_id => tags_id.id);
                }
        },

        },

        methods: {
            saveForm(id) {
                event.preventDefault();
                let app = this;
                const config = {
                    headers: { 'Content-type': 'multipart/form-data' }
                }
                let formData = new FormData();

                // formData.append('image', app.imageData)
                formData.append('post', JSON.stringify(app.post))
                formData.append('_method', 'PATCH')

                axios.post('/api/admin/vposts/' + id, formData, config)
                .then(function(response) {
                    app.$router.push({path: '/'});
                }).catch(error => {
                    console.log(error.message);
                })
            },



          previewImage: function(event) {
            // Reference to the DOM input element
            var input = event.target;
            // Ensure that you have a file before attempting to read it
            if (input.files && input.files[0]) {
                // create a new FileReader to read this image and convert to base64 format
                var reader = new FileReader();
                // Define a callback function to run, when FileReader finishes its job
                this.imageData = input.files[0]
                reader.onload = (e) => {
                    // Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
                    // Read image as base64 and set to imageData
                    this.imageData = input.files[0];
                }
                // Start the reader job - read file as a data url (base64 format)
                reader.readAsDataURL(input.files[0]);
            }

            }

        },

    }

</script>

<style>

.file-upload-form, .image-preview {
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    padding: 20px;
}
img.preview {
    width: 200px;
    background-color: white;
    border: 1px solid #DDD;
    padding: 5px;
}

</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>