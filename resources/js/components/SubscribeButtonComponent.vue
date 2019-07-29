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
                axios.post(location.pathname + '/subscriptions');

                this.active = true;

                flash('Subscribed');
            }
        }
    }
</script>