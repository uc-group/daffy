import Api from './base';

export default {
    list() {
        return Api.get('dockerfile_list');
    },
    create(name, baseImage, description) {
        return Api.post('dockerfile_create', {}, {name, baseImage, description});
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
