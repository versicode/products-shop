<form method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="name">Name</label>
                <input id="name"
                       type="text"
                       name="name"
                       class="form-control"
                       value="<?=$product['name']; ?>"
                       placeholder="Coffee"
                       required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description"
                          cols="30"
                          rows="10"
                          class="form-control"
                          placeholder="Best coffe!"
                          required><?=$product['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input id="price"
                       type="number"
                       step="0.001"
                       name="price"
                       class="form-control"
                       value="<?=$product['price']; ?>"
                       placeholder="123.23"
                       required>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="picture">Picture</label>
                <input id="picture"
                       type="file"
                       name="picture"
                       class="form-control-file"
                       >
            </div>
            <img width="300px" height="300px" src="/uploads/products/<?=$product['picture_name'] ? $product['picture_name'] : 'default.png';?>" alt="">
        </div>
    </div>
    <input type="submit" name="submit" class="btn btn-primary" value="Submit" />
</form>
