<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <title>Vue1</title>
</head>
<body>
<h2>Belajar Vue</h2>
<div id="root">
 <input type="text" id="input" v-model="message">
 <p>Value dari input text adalah: {{message}}.</p>
</div>
<script src="https://unpkg.com/vue"></script>
<script>
 new Vue ({
  el: '#root',
  data: {
   message: 'Hello World'
  }
 })
</script>
</body>
</html>
