
<?php include('header.php'); ?>
        <form action="." method="GET" class="text-center">
            <div class="form_select_container">
                <select name="authorId" class="form-control">
                <option value="0">View All Authors</option>
                <?php foreach($authors as $author) : ?>
                    <option value=<?= $author['id'] ?>><?= $author['author'] ?></option>
                <?php endforeach; ?>
                </select>
                <select name="categoryId" class="form-control">
                <option value="0">View All Categories</option>
                <?php foreach($categories as $category) : ?>
                    <option value=<?= $category['id'] ?>><?= $category['category'] ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="form_btn_container">
                <button class="btn btn-primary" type="submit">Submit</button>
                <button class="btn btn-warning" type="reset">Reset</button>
            </div>
        </form>
        <div class="table-responsive the_table">
            <table class="table table-light table-hover table-bordered table-striped">
                <?php if(!empty($quotes)) { foreach ($quotes as $quote) : ?>
                <thead>
                    <tr>
                        <th colspan="2">"<?= $quote['quote']; ?>."</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="col"><?= $quote['author'] ?></th>
                        <td scope="col"><?= $quote['category'] ?></th>
                    </tr>
                </tbody>
                <?php endforeach; } else { ?> <h2>No quotes available</h2> <?php }?>
            </table>
        </div>
<?php include('footer.php'); ?>