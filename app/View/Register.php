<h1>Register</h1>
<?php $form = \App\Core\Form\Form::begin('', 'post')?>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'firstname')?>
        </div>
        <div class="col">
            <?= $form->field($model, 'lastname')?>
        </div>
    </div>
    <?= $form->field($model, 'email')?>
    <?= $form->field($model, 'password')->passwordField()?>
    <?= $form->field($model, 'confirmPassword')->passwordField()?>
    <button type="submit" class="btn btn-primary mt-3">Submit</button>
<?php \App\Core\Form\Form::end()?>