<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a href="#" class="nav-link" data-toggle="dropdown" role="button"
        aria-haspopup="true" aria-expanded="false" v-pre>
            <span class="glyphicon glyphicon-bell"></span>
            Notifications
        </a>

        <ul class="dropdown-menu dropdown-menu-right">
            <li v-for="notification in notifications" v-bind:key="notification.id">
                <a :href="notification.data.link" 
                    class="dropdown-item" 
                    v-text="notification.data.message" 
                    @click="markAsRead(notification)"
                ></a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        data() {
            return { notifications: false }
        },

        created() {
            axios.get("/profiles/" + window.App.user.name + "/notifications")
                .then(response => this.notifications = response.data);
        },

        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id);
            }
        }
    }
</script>

