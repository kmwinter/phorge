<?php
pminclude('phorge:core.ValidatorErrors');
interface Validating {

    public function validate(ValidatorErrors $errors, Request $request, Response $response);
}

?>
