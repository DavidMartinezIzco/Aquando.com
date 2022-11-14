## Proto-Plan PES Aquando Actual

> Dependiendo del tipo de Hosting hay 3 posibles caminos a seguir

* Host en servidor propio
* Host Cloud en Linux
* Host Cloud en Windows

> Los posibles cambios en Aquando pueden ser de:

* Cambios en el back-end
* Cambios de arquitectura
* Cambios de red

***
## Host Propio:

> Dejamos Aquando funcionando en un servidor dentro de nuestras oficinas.
> Esto nos deja dos opciones:

### Justo donde esta:

#### Cambios en back-end:

Nada

#### Cambios de arquitectura:

Nada

#### Cambios de red:

poner HTTPS y opcionalmente, alias para acortar URL.

### En otra máquina:

#### Cambios en back-end:

* Si mantenemos arquitectura: Nada
* Cambiamos arquitectura: Cambios menores en algunas constantes (IPs y Direcciones)

#### Cambios de arquitectura:

* Si mantenemos arquitectura: Nada
* Cambiamos arquitectura: Depende. Pero este caso no es necesario dentro de nuestro host.

#### Cambios de red:

* Mantenemos/Cambiamos arquitectura: Poner HTTPS y opcionalmente, alias para acortar URL.

***
## Host Cloud en Linux:

> Dejamos Aquando funcionando en un Hosting que este basado en maquina Linux. Al ya ser una máquina no se podría mantener la arquitectura actual

### Cambios en back-end:

Cambiar direcciones a recursos de PostgreSQL y poco mas

### Cambios de arquitectura:

* convertir los procesos de Node-red a Linux
* convertir PostgreSQL a Linux
* convertir Talend a Linux
* recolocar el directorio de Aquando dependiendo del tipo de Apache que use

### Cambios de red:

poner algún alias para acortar URL

***
## Host Cloud en Windows:

> Dejamos Aquando en un Hosting que este basado en una Maquina Windows que como en el caso anterior no permita tener una maquina dentro (en este caso el Debian).

#### Cambios en back-end:

* Convertir Aquando a Windows con XAMPP
* Cambios en direcciones a recursos internos y externos a Aquando
* Algunos cambios de multi-threading en Aquando para rendimientos mas bajos

#### Cambios de arquitectura:

* Cambio de directorio de Aquando
* Instalación de XAMPP en el Host

#### Cambios de red:

alias para acortar URL.