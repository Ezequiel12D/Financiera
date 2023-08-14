<link href="../css/bootstrap.css" rel="stylesheet" />
<section class="h-100 bg-dark">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="card card-registration my-4">
                    <div class="row g-0">
                        <div class="col-xl-6 d-none d-xl-block">
                            <img src="" alt="Sample photo" class="img-fluid" style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;" />
                        </div>
                        <div class="col-xl-6">
                            <div class="card-body p-md-5 text-black">
                                <h3 class="mb-5 text-uppercase">Registro de Usuario</h3>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-outline">
                                            <label class="form-label" for="form3Example1m">Nombre/s:</label>
                                            <input type="text" id="form3Example1m" class="form-control form-control-lg" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-outline">
                                            <label class="form-label" for="form3Example1n">Apellido/s:</label>
                                            <input type="text" id="form3Example1n" class="form-control form-control-lg" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label class="form-label" for="form3Example8">Dni</label>
                                    <input type="text" id="form3Example8" class="form-control form-control-lg" />
                                </div>

                                <div class="col-md-6 mb-4">
                                    <select>
                                        <option value="Elejir provincia">Elegir provincia</option>
                                        <option value="Buenos Aires">Buenos Aires</option>
                                        <option value="Catamarca">Catamarca</option>
                                        <option value="Chaco">Chaco</option>
                                        <option value="Chubut">Chubut</option>
                                        <option value="Córdoba">Córdoba</option>
                                        <option value="Corrientes">Corrientes</option>
                                        <option value="Entre Ríos">Entre Ríos</option>
                                        <option value="Formosa">Formosa</option>
                                        <option value="Jujuy">Jujuy</option>
                                        <option value="La Pampa">La Pampa</option>
                                        <option value="La Rioja">La Rioja</option>
                                        <option value="Mendoza">Mendoza</option>
                                        <option value="Misiones">Misiones</option>
                                        <option value="Neuquén">Neuquén</option>
                                        <option value="Río Negro">Río Negro</option>
                                        <option value="Salta">Salta</option>
                                        <option value="San Juan">San Juan</option>
                                        <option value="San Luis">San Luis</option>
                                        <option value="Santa Cruz">Santa Cruz</option>
                                        <option value="Santa Fe">Santa Fe</option>
                                        <option value="Santiago del Estero">Santiago del Estero</option>
                                        <option value="Tierra del Fuego">Tierra del Fuego</option>
                                        <option value="Tucumán">Tucumán</option>
                                    </select>


                                </div>
                            </div>

                            <form>
                                <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                                <input type="date" id="fechaNacimiento" name="fechaNacimiento">
                            </form>


                            <div class="form-outline mb-4">
                                <label class="form-label" for="form3Example99">Telefono:</label>
                                <input type="text" id="form3Example99" class="form-control form-control-lg" />
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="form3Example99">Correo electronico:</label>
                                <input type="text" id="form3Example99" class="form-control form-control-lg" />
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="form3Example9">Contraseña: </label>
                                <div class="input-group">
                                    <input type="password" id="form3Example9" class="form-control form-control-lg">
                                    <button id="togglePassword" class="btn btn-outline-secondary" type="button">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <p class="warning" style="color: red; display: none;">La contraseña debe contener al menos un número.</p>
                                <p class="warning" style="color: red; display: none;">La contraseña debe contener al menos una letra mayúscula.</p>
                                <p id="lengthCounter" style="display: none;">Longitud de contraseña: <span id="lengthValue">0</span></p>
                            </div>

                            <script>
                                const passwordInput = document.getElementById('form3Example9');
                                const togglePasswordButton = document.getElementById('togglePassword');
                                const warnings = document.querySelectorAll('.warning');
                                const lengthValue = document.getElementById('lengthValue');

                                togglePasswordButton.addEventListener('click', () => {
                                    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                                });

                                passwordInput.addEventListener('input', () => {
                                    const password = passwordInput.value;
                                    const hasUppercase = /[A-Z]/.test(password);
                                    const hasNumber = /\d/.test(password);

                                    warnings[0].style.display = hasNumber ? 'none' : 'block';
                                    warnings[1].style.display = hasUppercase ? 'none' : 'block';

                                    lengthValue.textContent = password.length;
                                    lengthCounter.style.display = 'block';
                                    lengthValue.style.color = password.length > 8 ? 'green' : 'black';
                                });
                            </script>
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

                            <br>
                            <div class="d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-warning btn-lg ms-2">Registrarse</button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</section>