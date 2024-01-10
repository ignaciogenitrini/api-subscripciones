/** En el siguiente documento se redacta la información necesaria para la correcta utilización de la API de subscripciones **/

/** Lista de endpoints **/

Finalidad: Consultar los datos de las suscripciones activas.(Respuesta en formato JSON).
Endpoint: http://localhost/CodeIgniter/index.php/api/subscriptions/subsbyid/$1 

Método: GET
Payload: Se requiere pasar por parametro un id: 1 - activo / 2 - inactivo. Este parametro se utilizará para traer las subscripciones correspondientes.
Response: Listado de subscripciones por estado activas/inactivas.
Status: 200

------

Finalidad: Guardar el plan de suscripción de un cliente.
Endpoint: http://localhost/CodeIgniter/index.php/api/subscriptions/store

Método: POST
Payload: Se requiere enviar una lista de parámetros para la subscripción de un cliente, el mail de dicha subscripción no puede ser repetido a nivel de base de datos / codigo.
Lista de parámetros (requeridos):

email_subscription (string)
name_subscription (string)
payment_type_id (string) - ids válidos: 1 - débito, 2 - crédito.
plan_id (string) - ids válidos: 1 - básico, 2 - pro, 3 - empresas.
status_sub_id (string) - ids válidos: 1 - activa, 2 - inactiva.

Response: Mensaje de confirmación de la subscripción generada. 
Status: 200

------

Finalidad: Poder generar un lote de cobro.
Endpoint: http://localhost/CodeIgniter/index.php/api/lots/store
Método: POST
Payload: No recibe parámetros ya que se considera la utilización para un posible cronjob. Pegandole al endpoint generaría el correspondiente lote a nivel de base de datos de las subscripciones activas hasta la fecha con formato JSON.

Response: Mensaje de confirmación del lote generada. 
Status: 200

------

Finalidad: Consultar el detalle del lote. (Respuesta en formato JSON).
Endpoint: http://localhost/CodeIgniter/index.php/api/lots/getlotdetail
Método: POST
Payload: Se requiere enviar el parámetro date con un string que haga referencia a la fecha correspondiente al lote.
Lista de parámetros (requeridos):

date (string) - 2024-05-05 / 2024-05-06 / 2024-05-08 / 2024-05-09 / etc.

Response: Json con la información del lote generado en dicha fecha.
Status: 200

------

Finalidad: Consultar el monto total y cantidad de cobros por lote. (Respuesta en formato JSON).
Endpoint: http://localhost/CodeIgniter/index.php/api/lots/getlotamount
Método: POST
Payload: Se requiere enviar el parámetro date con un string que haga referencia a la fecha correspondiente al lote.
Lista de parámetros (requeridos):

date (string) - 2024-05-05 / 2024-05-06 / 2024-05-08 / 2024-05-09 / etc.

Response: Json con la información del monto total y cantidad de cobros por lote.
Status: 200














