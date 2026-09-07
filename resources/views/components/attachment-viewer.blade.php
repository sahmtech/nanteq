<!DOCTYPE html>
<html>
<body>
    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 bg-white rounded shadow">
                <video controls class="w-full h-auto rounded">
                    <source src="{{ url('xray-video', $id) }}" type="video/mp4">
                </video>
        </div>
        <div class="p-4 bg-white rounded shadow">
                <video controls class="w-full h-auto rounded">
                    <source src="{{ url('natural-video', $id) }}" type="video/mp4">
                </video>
        </div>
    </div>
</body>

</html>
