<template>
    <div>
        <ul>
            <li v-for="service in services" :key="service.name">
                <span>{{ service.name }}</span>
            </li>
        </ul>
        <button type="button" class="btn btn-primary" @click="addService">Dodaj nowy</button>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'd-service-list',
        computed: mapState({
            services: state => state.dockerCompose.services
        }),
        methods: {
            addService() {
                this.$store.commit('dockerCompose/addService', {name: "test_service"});
            }
        }
    }
</script>