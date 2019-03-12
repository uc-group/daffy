import Api from './base';

export default {
    list() {
        return Api.get('dockerfile_list');
    },
    create(dockerfile) {
        return Api.post('dockerfile_create', {}, dockerfile);
    },
    remove(id) {
        return Api.get('dockerfile_remove', {id});
    },
    viewUrl(id) {
        return Api.route('dockerfile_view', {id});
    },
    build(id) {
        return Api.get('dockerfile_build', {id});
    },
    save(id, data) {
        return Api.post('dockerfile_save', {id}, data);
    }
}
