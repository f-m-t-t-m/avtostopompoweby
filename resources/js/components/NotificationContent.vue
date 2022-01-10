<template>
<div>
    <p>Новое сообщение </p>
    <p>в разделе: {{ section }}</p>
    <p>предмета: {{ subject }}</p>
</div>
</template>

<script>
export default {
    name: "NotificationContent",
    props: [
        'section',
        'subject'
    ],
}
</script>

<style scoped>

</style>
