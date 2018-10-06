<template>
    <svg>
        <use :xlink:href="logo"></use>
    </svg>
</template>

<script>
    import logo from '../../svg/daffy-logo.svg';

    export default {
        data() {
            return { logo }
        }
    }
</script>
