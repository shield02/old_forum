<template>
    <button :class="classes" @click="subscribe">Subcribe</button>
</template>

<script>
    export default {
        props: ['active'],

        computed: {
            classes() {
                return [
                    'btn', 
                    this.active ? 'btn-primary' : 'btn-light'
                ];
            }
        },

        methods: {
            subscribe() {
                axios[
                    (this.active ? 'delete' : 'post')
                ](location.pathname + '/subscriptions');

                this.active = ! this.active;
            }
        }
    }
</script>