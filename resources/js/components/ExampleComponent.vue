<template></template>

<script>
    import NotificationContent from "./NotificationContent";
    export default {
        props: [
            'user'
        ],
        data() {
            return {
                section: '',
                subject: '',
            }
        },
        mounted() {
            console.log('example')
            window.Echo.private('notification.'+this.user.id).listen('NewMessage', (e) => {
                console.log(e);

                const content = {
                    component: NotificationContent,
                    props: {
                        section: e.section.name,
                        subject: e.subject.name,
                    }
                }
                this.$toast(content , {
                    position: "bottom-right",
                    timeout: 5000,
                    closeOnClick: true,
                    pauseOnFocusLoss: true,
                    pauseOnHover: true,
                    draggable: true,
                    draggablePercent: 0.6,
                    showCloseButtonOnHover: false,
                    hideProgressBar: false,
                    closeButton: "button",
                    icon: true,
                    rtl: false,
                    onClick: function () {
                        window.open(`http://localhost/section/${e.section.id}?page=${e.lastPage}#to_${e.comment.id}`);
                    },
                });
            });
        },
    }
</script>

<style>
    .Vue-Toastification__toast--default {
        background-color: hsl(218,48%,45%);
    }
</style>
