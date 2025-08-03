<?php

namespace Database\Seeders;

use App\Models\Missionary;
use App\Models\Testimony;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TestimoniesSeeder extends Seeder
{
    const array TESTIMONIES = [
        [
            'name' => 'Hno. Luis Fdo.',
            'content' => 'A finales de 2023, me embarqué en una búsqueda de la verdad sin límites y, sobre todo, con claridad. Decidido a conocer el propósito del ser humano en este mundo, empecé a estudiar e indagar en todo tipo de religiones, creencias, dogmas, e inclusive cuestionando al Dios de la Biblia y a su ungido, nuestro Señor Jesucristo. Desde ese momento, Dios empezó a obrar en mi vida, guiándome en mi búsqueda de la Verdad. Leía la Biblia y trataba de entender los designios de Dios y su plan para nosotros. Cuanto más me sumergía en la búsqueda de la verdad.
Fue un 12 de marzo de 2024 que, abrumado por la culpa de mi pecado y avergonzado por no haber creído en nuestro Salvador todos estos años, de forma natural pero a la vez extraordinaria, durante el trayecto del trabajo a mi casa, me arrepentí de forma total de mis pecados y me humillé ante la gloriosa presencia de nuestro Salvador. Sumido en un mar de lágrimas, pedía perdón por mi pecado, a la vez que me regocijaba del gozo de haber recibido el Espíritu Santo. Ese mismo día, por obra del Espíritu de Dios, corté de tajo mis adicciones y otras prácticas pecaminosas.
Empecé a congregarme en una iglesia en la cual me bauticé en abril de 2024. Iglesia que abandoné por fuertes discrepancias en doctrinas básicas como la salvación únicamente por fe.
Gracias a Dios, al pasar un par de semanas, encontré la Iglesia Bautista Fundamental, liderada por el pastor Roberto Goins y su esposa, a través de una búsqueda en Google. Desde el primer momento que entré al culto, sentí el Espíritu Santo obrando en ese lugar, a través de los hermanos y hermanas que ahí se congregaban. Por fin, había encontrado una congregación bíblica y apegada a la palabra de Dios. Hasta hoy, es un gozo muy grande el poder congregar con hermanos en la fe, siempre con un mismo objetivo: honrar, adorar y venerar al único Dios verdadero y al único mediador entre nosotros y Él: nuestro Señor Jesucristo. Pido a Dios muchos años de congregar en la Iglesia Bautista Fundamental, creciendo en sabiduría para ser sal y luz de este mundo.
Amén.'
        ],
        [
            'name' => 'Hno. Aurelio',
            'content' => 'Doy gracias a Dios por haberme guiado a la Iglesia Bautista Fundamental de Arizona, un lugar donde la verdad de la Palabra de Dios es predicada con claridad, firmeza y amor. Desde que llegué, he sido bendecido por el liderazgo fiel del Pastor Roberto Goins, un siervo comprometido con Dios y con su iglesia.
Cada mensaje bíblico ha sido una fuente de ánimo, corrección y crecimiento espiritual. El ambiente en la iglesia es cálido, acogedor y centrado en Cristo. No solo he encontrado una iglesia, sino una familia espiritual que ora, sirve y camina unida en la fe.
Quiero agradecer también de manera especial a la familia del pastor. Su entrega, amor y apoyo incondicional al ministerio son una inspiración para todos nosotros. Sabemos que el trabajo del siervo de Dios no sería posible sin el respaldo firme y amoroso de su familia. Su ejemplo de humildad, hospitalidad y servicio es una gran bendición para esta congregación.
Gracias al Señor por usar poderosamente al Pastor Goins y a su familia. Oro para que Dios continúe fortaleciendo su ministerio y multiplicando el fruto de su labor para Su gloria.
¡A Dios sea la gloria por lo que Él está haciendo en la Iglesia Bautista Fundamental de Casa Grande, Arizona.',
        ],
        [
            'name' => 'Familia Lara Flores',
            'content' => 'Llegamos a la Iglesia Bíblica Bautista Fundamental de Arizona en un momento en que nuestras vidas estaban llenas de dudas, luchas y heridas del pasado. No sabíamos que Dios ya tenía un plan perfecto para transformarnos. Desde el primer día, fuimos recibidos con amor genuino, con la verdad de Su Palabra y con una comunidad que nos abrazó como familia.
A través de la predicación fiel, el estudio bíblico y el compañerismo cristiano, Dios comenzó a obrar poderosamente en nuestras vidas. Aprendimos lo que significa vivir conforme a Su voluntad, a tener una relación personal con Cristo, y a confiar en Su gracia en cada área de nuestro hogar. Nuestro matrimonio fue restaurado, nuestra fe fortalecida y hoy caminamos con propósito, sabiendo que somos parte de algo eterno.
Damos gracias a Dios por usar esta iglesia como instrumento de bendición, El pastor Roberto Goins y familia que nos han guiado con paciencia y verdad. Nuestra vida no es la misma desde que Cristo tomó el control.
A Él sea toda la gloria.',
        ]
    ];

    public function run(): void
    {
        Testimony::insert(self::TESTIMONIES);
    }
}
