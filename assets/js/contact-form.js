(function(root, factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory();
    } else {
        root.ContactForm = factory();
    }
}(this, function() {
    "use strict";

    var ContactForm = function(form, options) {
        if (!this || !(this instanceof ContactForm)) {
            return new ContactForm(form, options);
        }

        if (!form || !options) {
            return;
        }

        this.form = form instanceof Node ? form : document.querySelector(form);
        this.endpoint = options.endpoint;

        this.send();
    };

    ContactForm.prototype = {
        hasClass: function(el, name) {
            return new RegExp('(\\s|^)' + name + '(\\s|$)').test(el.className);
        },
        addClass: function(el, name) {
            if (!this.hasClass(el, name)) {
                el.className += (el.className ? ' ' : '') + name;
            }
        },
        removeClass: function(el, name) {
            if (this.hasClass(el, name)) {
                el.className = el.className.replace(new RegExp('(\\s|^)' + name + '(\\s|$)'), ' ').replace(/^\s+|\s+$/g, '');
            }
        },
        each: function(collection, iterator) {
            var i, len;

            for (i = 0, len = collection.length; i < len; i += 1) {
                iterator(collection[i], i, collection);
            }
        },
        template: function(string, data) {
            var piece;

            for (piece in data) {
                if (Object.prototype.hasOwnProperty.call(data, piece)) {
                    string = string.replace(new RegExp('{' + piece + '}', 'g'), data[piece]);
                }
            }

            return string;
        },
        empty: function(el) {
            while (el.firstChild) {
                el.removeChild(el.firstChild);
            }
        },
        removeElementsByClass: function(className) {
            var elements = document.getElementsByClassName(className);

            while (elements.length > 0) {
                elements[0].parentNode.removeChild(elements[0]);
            }
        },
        post: function(path, data, success, fail) {
            var xhttp = new XMLHttpRequest();

            xhttp.open('POST', path, true);
            xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        var response = '';
                        try {
                            response = JSON.parse(this.responseText);
                        } catch (err) {
                            response = this.responseText;
                        }
                        success.call(this, response);
                    } else {
                        fail.call(this, this.responseText);
                    }
                }
            };
            xhttp.send(data);
            xhttp = null;
        },
        param: function(data) {
            var params = typeof data === 'string' ? data : Object.keys(data).map(
                function(k) { return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]); }
            ).join('&');

            return params;
        },
        send: function() {
            this.form.addEventListener('submit', function(e) {
                e.preventDefault();

                var elements = document.querySelectorAll('.form-control'),
                    formData;

                this.each(elements, function(el, i) {
                    if (this.hasClass(el.parentNode, 'has-error')) {
                        this.removeClass(el.parentNode, 'has-error');
                        this.removeElementsByClass('help-block');
                    }
                }.bind(this));

                formData = {
                    'name': document.querySelector('input[name="form-name"]').value,
                    'dni': document.querySelector('input[name="form-dni"]').value,
                    'data_nacemento': document.querySelector('input[name="form-data_nacemento"]').value,
                    'enderezo': document.querySelector('input[name="form-enderezo"]').value,
                    'cp': document.querySelector('input[name="form-cp"]').value,
                    'cidade': document.querySelector('input[name="form-cidade"]').value,
                    'ocupacion': document.querySelector('input[name="form-ocupacion"]').value,
                    'telefono': document.querySelector('input[name="form-telefono"]').value,
                    'email': document.querySelector('input[name="form-email"]').value,
                    'udc': document.querySelector('input[name="form-udc"]').value
                };

                this.post(this.endpoint, this.param(formData), this.feedback.bind(this), this.fail.bind(this));
            }.bind(this), false);
        },
        feedback: function(data) {
            if (!data.success) {
                if (data.errors.name) {
                    var name = document.querySelector('input[name="form-name"]').parentNode,
                        error;

                    this.addClass(name, 'has-error');
                    error = this.template(
                        '<span class="help-block">{report}</span>', {
                            report: data.errors.name
                        });

                    name.insertAdjacentHTML('beforeend', error);
                }

                if (data.errors.dni) {
                    var dni = document.querySelector('input[name="form-dni"]').parentNode,
                        error;

                    this.addClass(dni, 'has-error');
                    error = this.template(
                        '<span class="help-block">{report}</span>', {
                            report: data.errors.dni
                        });

                    dni.insertAdjacentHTML('beforeend', error);
                }

                if (data.errors.data_nacemento) {
                    var data_nacemento = document.querySelector('input[name="form-data_nacemento"]').parentNode,
                        error;

                    this.addClass(data_nacemento, 'has-error');
                    error = this.template(
                        '<span class="help-block">{report}</span>', {
                            report: data.errors.data_nacemento
                        });

                    data_nacemento.insertAdjacentHTML('beforeend', error);
                }

                if (data.errors.enderezo) {
                    var enderezo = document.querySelector('input[name="form-enderezo"]').parentNode,
                        error;

                    this.addClass(enderezo, 'has-error');
                    error = this.template(
                        '<span class="help-block">{report}</span>', {
                            report: data.errors.enderezo
                        });

                    enderezo.insertAdjacentHTML('beforeend', error);
                }

                if (data.errors.cp) {
                    var cp = document.querySelector('input[name="form-cp"]').parentNode,
                        error;

                    this.addClass(cp, 'has-error');
                    error = this.template(
                        '<span class="help-block">{report}</span>', {
                            report: data.errors.cp
                        });

                    cp.insertAdjacentHTML('beforeend', error);
                }

                if (data.errors.cidade) {
                    var cidade = document.querySelector('input[name="form-cidade"]').parentNode,
                        error;

                    this.addClass(cidade, 'has-error');
                    error = this.template(
                        '<span class="help-block">{report}</span>', {
                            report: data.errors.cidade
                        });

                    cidade.insertAdjacentHTML('beforeend', error);
                }

                if (data.errors.ocupacion) {
                    var ocupacion = document.querySelector('input[name="form-ocupacion"]').parentNode,
                        error;

                    this.addClass(ocupacion, 'has-error');
                    error = this.template(
                        '<span class="help-block">{report}</span>', {
                            report: data.errors.ocupacion
                        });

                    ocupacion.insertAdjacentHTML('beforeend', error);
                }

                if (data.errors.telefono) {
                    var telefono = document.querySelector('input[name="form-telefono"]').parentNode,
                        error;

                    this.addClass(telefono, 'has-error');
                    error = this.template(
                        '<span class="help-block">{report}</span>', {
                            report: data.errors.telefono
                        });

                    telefono.insertAdjacentHTML('beforeend', error);
                }

                if (data.errors.email) {
                    var email = document.querySelector('input[name="form-email"]').parentNode,
                        error;

                    this.addClass(email, 'has-error');
                    error = this.template(
                        '<span class="help-block">{report}</span>', {
                            report: data.errors.email
                        });

                    email.insertAdjacentHTML('beforeend', error);
                }

                if (data.errors.udc) {
                    var udc = document.querySelector('input[name="form-udc"]').parentNode,
                        error;

                    this.addClass(udc, 'has-error');
                    error = this.template(
                        '<span class="help-block">{report}</span>', {
                            report: data.errors.udc
                        });

                    udc.insertAdjacentHTML('beforeend', error);
                }

            } else {
                var success = this.template(
                    '<div class="alert alert-success">{report}</div>', {
                        report: data.message
                    });

                this.empty(this.form);
                this.form.insertAdjacentHTML('beforeend', success);
            }
        },
        fail: function(data) {
            console.log(data);
        }
    };

    return ContactForm;
}));