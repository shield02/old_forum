<template>
    <div>
        <div class="level">
            <img :src="avatar" :alt="user.name" width="50" height="50" class="mr-2">
            
            <h1 v-text="user.name"></h1>
        </div>

        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <image-upload-component  name="avatar" class="mr-1" @loaded="onLoad"></image-upload-component>
        </form>

    </div>
</template>

<script>
    import ImageUploadComponent from './ImageUploadComponent.vue';

    export default {
        props: ['user'],

        components: { ImageUploadComponent },

        data() {
            return {
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            }
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;

                this.persist(avatar.file);
            },

            persist(avatar) {
                let data = new FormData();

                data.append('avatar', avatar)
                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded successfully!!'));
            }
        }
    }
</script>