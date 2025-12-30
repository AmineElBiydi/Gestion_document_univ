import React, { useState, useRef, useEffect } from 'react';
import { FileText, Download, Pen, Trash2, Save } from 'lucide-react';
import { pdf } from '@react-pdf/renderer';
import ConventionPDF from './ConventionPDF';

interface InternshipData {
  studentName: string;
  filiere: string;
  dateDebut: string;
  dateFin: string;
  entrepriseNom: string;
  entrepriseAdresse: string;
  entrepriseTel: string;
  entrepriseEmail: string;
  entrepriseRepresentant: string;
  entrepriseQualite: string;
  encadrantNom: string;
  tuteurNom: string;
  themeStage: string;
  dateSignature: string;
  // Index signature to allow dynamic access
  [key: string]: string;
}

interface Signatures {
  student: string | null;
  coordinator: string | null;
  establishment: string | null;
  enterprise: string | null;
}

type SignatureType = keyof Signatures;

const StageConventionTemplate = () => {
  const [data, setData] = useState<InternshipData>({
    studentName: 'LYAMANI ISMAIL',
    filiere: 'Génie Informatique 1ère année',
    dateDebut: '2025-07-01',
    dateFin: '2025-08-01',
    entrepriseNom: 'CEGEDIM',
    entrepriseAdresse: 'Arribat Center - Avenue Omar Ibn Khattab, Immeuble D et E - 2ème étage Avenue Omar Ibn Khattad, Agdal Rabat, 10090, MA',
    entrepriseTel: '+212 5380-45800',
    entrepriseEmail: 'direction.maroc@cegedim.com',
    entrepriseRepresentant: '',
    entrepriseQualite: '',
    encadrantNom: 'Abdenaceur RAHALI',
    tuteurNom: 'ABTOY Anour',
    themeStage: '',
    dateSignature: '23-Jun-2025 19:17:58'
  });

  const [signatures, setSignatures] = useState<Signatures>({
    student: null,
    coordinator: null,
    establishment: null,
    enterprise: null
  });

  const [isDrawing, setIsDrawing] = useState(false);
  const [currentSignature, setCurrentSignature] = useState<SignatureType | null>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  useEffect(() => {
    if (isModalOpen && canvasRef.current) {
      const canvas = canvasRef.current;
      const ctx = canvas.getContext('2d');
      if (ctx) {
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
      }
    }
  }, [isModalOpen]);

  const startDrawing = (e: React.MouseEvent<HTMLCanvasElement>) => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const rect = canvas.getBoundingClientRect();
    const ctx = canvas.getContext('2d');

    if (ctx) {
      setIsDrawing(true);
      ctx.beginPath();
      ctx.moveTo(
        e.clientX - rect.left,
        e.clientY - rect.top
      );
    }
  };

  const draw = (e: React.MouseEvent<HTMLCanvasElement>) => {
    if (!isDrawing) return;

    const canvas = canvasRef.current;
    if (!canvas) return;
    const rect = canvas.getBoundingClientRect();
    const ctx = canvas.getContext('2d');

    if (ctx) {
      ctx.strokeStyle = '#000';
      ctx.lineWidth = 2;
      ctx.lineCap = 'round';
      ctx.lineTo(
        e.clientX - rect.left,
        e.clientY - rect.top
      );
      ctx.stroke();
    }
  };

  const stopDrawing = () => {
    setIsDrawing(false);
  };

  const clearCanvas = () => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (ctx) {
      ctx.fillStyle = 'white';
      ctx.fillRect(0, 0, canvas.width, canvas.height);
    }
  };

  const saveSignature = () => {
    const canvas = canvasRef.current;
    if (!canvas || !currentSignature) return;
    const signatureData = canvas.toDataURL('image/png');
    setSignatures({ ...signatures, [currentSignature]: signatureData });
    setIsModalOpen(false);
    setCurrentSignature(null);
  };

  const openSignatureModal = (type: SignatureType) => {
    setCurrentSignature(type);
    setIsModalOpen(true);
  };

  const deleteSignature = (type: SignatureType) => {
    setSignatures({ ...signatures, [type]: null });
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData({ ...data, [e.target.name]: e.target.value });
  };

  const generatePDF = async () => {
    try {
      // Generate PDF blob
      const blob = await pdf(<ConventionPDF data={data} signatures={signatures} />).toBlob();

      // Create download link
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = `Convention_Stage_${data.studentName.replace(/\s+/g, '_')}.pdf`;

      // Trigger download
      document.body.appendChild(link);
      link.click();

      // Cleanup
      document.body.removeChild(link);
      URL.revokeObjectURL(url);
    } catch (error) {
      console.error('Erreur lors de la génération du PDF:', error);
      alert('Une erreur est survenue lors de la génération du PDF.');
    }
  };

  return (
    <div className="min-h-screen bg-gray-100 p-8">
      <div className="max-w-6xl mx-auto">
        {/* Signature Modal */}
        {isModalOpen && (
          <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
              <h3 className="text-xl font-bold mb-4">Dessiner la signature</h3>
              <div className="border-2 border-gray-300 rounded-lg mb-4">
                <canvas
                  ref={canvasRef}
                  width={600}
                  height={200}
                  className="w-full cursor-crosshair"
                  onMouseDown={startDrawing}
                  onMouseMove={draw}
                  onMouseUp={stopDrawing}
                  onMouseLeave={stopDrawing}
                />
              </div>
              <div className="flex gap-4 justify-end">
                <button
                  onClick={clearCanvas}
                  className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 flex items-center gap-2"
                >
                  <Trash2 className="w-4 h-4" />
                  Effacer
                </button>
                <button
                  onClick={() => {
                    setIsModalOpen(false);
                    setCurrentSignature(null);
                  }}
                  className="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                >
                  Annuler
                </button>
                <button
                  onClick={saveSignature}
                  className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center gap-2"
                >
                  <Save className="w-4 h-4" />
                  Sauvegarder
                </button>
              </div>
            </div>
          </div>
        )}
        {/* Form Section */}
        <div className="bg-white rounded-lg shadow-lg p-6 mb-8">
          <h2 className="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
            <FileText className="w-6 h-6" />
            Données de la Convention de Stage
          </h2>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Nom de l'étudiant</label>
              <input
                type="text"
                name="studentName"
                value={data.studentName}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Filière</label>
              <input
                type="text"
                name="filiere"
                value={data.filiere}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Date début</label>
              <input
                type="date"
                name="dateDebut"
                value={data.dateDebut}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
              <input
                type="date"
                name="dateFin"
                value={data.dateFin}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Nom de l'entreprise</label>
              <input
                type="text"
                name="entrepriseNom"
                value={data.entrepriseNom}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Téléphone entreprise</label>
              <input
                type="text"
                name="entrepriseTel"
                value={data.entrepriseTel}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>

            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-gray-700 mb-1">Adresse entreprise</label>
              <input
                type="text"
                name="entrepriseAdresse"
                value={data.entrepriseAdresse}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Email entreprise</label>
              <input
                type="email"
                name="entrepriseEmail"
                value={data.entrepriseEmail}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Encadrant</label>
              <input
                type="text"
                name="encadrantNom"
                value={data.encadrantNom}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Tuteur pédagogique</label>
              <input
                type="text"
                name="tuteurNom"
                value={data.tuteurNom}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>

            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-gray-700 mb-1">Thème du stage</label>
              <input
                type="text"
                name="themeStage"
                value={data.themeStage}
                onChange={handleChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md"
              />
            </div>
          </div>

          <button
            onClick={generatePDF}
            className="mt-6 bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2"
          >
            <Download className="w-4 h-4" />
            Générer PDF
          </button>
        </div>

        {/* Preview Section */}
        <div className="bg-white rounded-lg shadow-lg p-8">
          <div className="max-w-4xl mx-auto">
            {/* Header */}
            <div className="flex justify-between items-start mb-8 pb-4 border-b-2 border-gray-300">
              <div className="text-sm">
                <p className="font-bold">Université Abdelmalek Essaâdi</p>
                <p className="font-bold">Ecole Nationale des Sciences</p>
                <p className="font-bold">Appliquées</p>
                <p className="font-bold">Tétouan</p>
              </div>

              <div className="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                <span className="text-xs">LOGO</span>
              </div>

              <div className="text-sm text-right" dir="rtl">
                <p className="font-bold">جامعة عبد المالك السعدي</p>
                <p className="font-bold">المدرسة الوطنية للعلوم التطبيقية</p>
                <p className="font-bold">تطوان</p>
              </div>
            </div>

            <h1 className="text-center text-2xl font-bold mb-2">CONVENTION DE STAGE</h1>
            <p className="text-center text-sm italic mb-6">(2 exemplaires imprimés en recto-verso)</p>

            <h2 className="text-center font-bold mb-4">ENTRE</h2>

            <div className="mb-6 text-sm">
              <p>L'École Nationale des Sciences Appliquées, Université Abdelmalek Essaâdi - Tétouan</p>
              <p>B.P. 2222, Mhannech II, Tétouan, Maroc</p>
              <p>Tél. +212 39 68 80 27; Fax. +212 39 99 46 24. Web. <span className="text-blue-600">https://ensa-tetouan.ac.ma</span></p>
              <p>Représenté par le Professeur Kamal REKLAOUI en qualité de Directeur.</p>
              <p className="text-right">Ci-après, dénommé <strong>l'Etablissement</strong></p>
            </div>

            <p className="text-center font-bold mb-4">ET</p>

            <div className="mb-6 text-sm">
              <p>La Société : <strong>{data.entrepriseNom}</strong></p>
              <p>Adresse : <strong>{data.entrepriseAdresse}</strong></p>
              <p>Tél : <strong>{data.entrepriseTel}</strong> Email: <strong>{data.entrepriseEmail}</strong></p>
              <p>Représentée par Monsieur ................................................ en qualité ................................................</p>
              <p className="text-right">Ci-après dénommée <strong>L'ENTREPRISE</strong></p>
            </div>

            <div className="mb-4">
              <h3 className="font-bold mb-2">Article 1 : Engagement</h3>
              <p className="text-sm mb-4">
                <strong>L'ENTREPRISE</strong> accepte de recevoir à titre de stagiaire <strong>{data.studentName}</strong> étudiant de la filière du Cycle Ingénieur « {data.filiere} » de l'ENSA de Tétouan, Université Abdelmalek Essaâdi (Tétouan), pour une période allant du <strong>{data.dateDebut}</strong> au <strong>{data.dateFin}</strong>
              </p>
              <p className="text-sm mb-4">
                En aucun cas, cette convention ne pourra autoriser les étudiants à s'absenter durant la période des contrôles ou des enseignements.
              </p>
            </div>

            <div className="mb-4">
              <h3 className="font-bold mb-2">Article 2 : Objet</h3>
              <p className="text-sm">
                Le stage aura pour objet essentiel d'assurer l'application pratique de l'enseignement donné par <strong>l'Etablissement</strong>, et ce, en organisant des visites sur les installations et en réalisant des études proposées par <strong>L'ENTREPRISE</strong>.
              </p>
            </div>

            <div className="mb-4">
              <h3 className="font-bold mb-2">Article 3 : Encadrement et suivi</h3>
              <p className="text-sm">
                Pour accompagner le Stagiaire durant son stage, et ainsi instaurer une véritable collaboration L'ENTREPRISE/Stagiaire/Etablissement, <strong>L'ENTREPRISE</strong> désigne Mme/Mr <strong>{data.encadrantNom}</strong> encadrant(e) et parrain(e), pour superviser et assurer la qualité du travail fourni par le Stagiaire. L'Établissement désigne <strong>{data.tuteurNom}</strong> en tant que tuteur qui procurera une assistance pédagogique.
              </p>
            </div>

            <div className="mb-4">
              <h3 className="font-bold mb-2">Article 4 : Programme:</h3>
              <p className="text-sm">
                Le thème du stage est: « <strong>{data.themeStage || '...........................................................................................'}</strong> »
              </p>
              <p className="text-sm">
                Ce programme a été défini conjointement par l'Etablissement, <strong>L'ENTREPRISE</strong> et le Stagiaire. Le contenu de ce programme doit permettre au Stagiaire une réflexion en relation avec les enseignements ou le projet de fin d'études qui s'inscrit dans le programme de formation de l'Établissement.
              </p>
            </div>

            <div className="mb-4">
              <h3 className="font-bold mb-2">Article 5 : Indemnité de stage</h3>
              <p className="text-sm">
                Au cours du stage, l'étudiant ne pourra prétendre à aucun salaire de la part de <strong>L'ENTREPRISE</strong>.
              </p>
            </div>
          </div>
        </div>

        {/* Page 2 */}
        <div className="bg-white rounded-lg shadow-lg p-8 mt-8">
          <div className="max-w-4xl mx-auto">
            <div className="mb-4 text-sm">
              <p>
                Cependant, si <strong>L'ENTREPRISE</strong> et l'étudiant le conviennent, ce dernier pourra recevoir une indemnité forfaitaire de la part de <strong>L'ENTREPRISE</strong> des frais occasionnés par la mission confiée à l'étudiant.
              </p>
            </div>

            <div className="mb-4">
              <h3 className="font-bold mb-2">Article 6 : Règlement</h3>
              <p className="text-sm mb-2">
                Pendant la durée du stage, le Stagiaire reste placé sous la responsabilité de <strong>l'Etablissement</strong>. Cependant, l'étudiant est tenu d'informer l'école dans un délai de <strong>24h sur toute modification</strong> portant sur la convention déjà signée, sinon il en assumera toute sa responsabilité sur son non-respect de la convention signée par l'école.
              </p>
              <p className="text-sm">
                Toutefois, le Stagiaire est soumis à la discipline et au règlement intérieur de <strong>L'ENTREPRISE</strong>. En cas de manquement, <strong>L'ENTREPRISE</strong> se réserve le droit de mettre fin au stage après en avoir convenu avec le Directeur de l'Établissement.
              </p>
            </div>

            <div className="mb-4">
              <h3 className="font-bold mb-2">Article 7 : Confidentialité</h3>
              <p className="text-sm">
                Le Stagiaire et l'ensemble des acteurs liés à son travail (l'administration de <strong>l'Etablissement</strong>, le parrain pédagogique ...) sont tenus au secret professionnel. Ils s'engagent à ne pas diffuser les informations recueillies à des fins de publications, conférences, communications, sans raccord préalable de <strong>L'ENTREPRISE</strong>. Cette obligation demeure valable après l'expiration du stage.
              </p>
            </div>

            <div className="mb-4">
              <h3 className="font-bold mb-2">Article 8 : Assurance accident de travail</h3>
              <p className="text-sm">
                Le stagiaire devra obligatoirement souscrire une assurance couvrant la Responsabilité Civile et Accident de Travail, durant les stages et trajets effectués. En cas d'accident de travail survenant durant la période du stage, <strong>L'ENTREPRISE</strong> s'engage à faire parvenir immédiatement à l'Établissement toutes les informations indispensables à la déclaration dudit accident.
              </p>
            </div>

            <div className="mb-4">
              <h3 className="font-bold mb-2">Article 9: Evaluation de L'ENTREPRISE</h3>
              <p className="text-sm">
                Le stage accompli, le parrain établira un rapport d'appréciations générales sur le travail effectué et le comportement du Stagiaire durant son séjour chez <strong>L'ENTREPRISE</strong>. <strong>L'ENTREPRISE</strong> remettra au Stagiaire une attestation indiquant la nature et la durée des travaux effectués.
              </p>
            </div>

            <div className="mb-6">
              <h3 className="font-bold mb-2">Article 10 : Rapport de stage</h3>
              <p className="text-sm">
                A l'issue de chaque stage, le Stagiaire rédigera un rapport de stage faisant état de ses travaux et de son vécu au sein de <strong>L'ENTREPRISE</strong>. Ce rapport sera communiqué à <strong>L'ENTREPRISE</strong> et restera strictement confidentiel.
              </p>
            </div>

            <p className="text-center mb-8">
              Fait à Tétouan en deux exemplaires, le <strong>{data.dateSignature}</strong>
            </p>

            <div className="grid grid-cols-2 gap-8 mb-8">
              <div className="text-center">
                <p className="mb-4">Nom et signature du Stagiaire</p>
                {signatures.student ? (
                  <div className="relative inline-block">
                    <img src={signatures.student} alt="Signature étudiant" className="h-20 mx-auto border border-gray-300 rounded" />
                    <button
                      onClick={() => deleteSignature('student')}
                      className="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                    >
                      <Trash2 className="w-3 h-3" />
                    </button>
                  </div>
                ) : (
                  <button
                    onClick={() => openSignatureModal('student')}
                    className="mx-auto flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                  >
                    <Pen className="w-4 h-4" />
                    Signer
                  </button>
                )}
                <p className="font-bold mt-4">{data.studentName}</p>
              </div>
              <div className="text-center">
                <p className="mb-4">Le Coordonnateur de la filière</p>
                {signatures.coordinator ? (
                  <div className="relative inline-block">
                    <img src={signatures.coordinator} alt="Signature coordinateur" className="h-20 mx-auto border border-gray-300 rounded" />
                    <button
                      onClick={() => deleteSignature('coordinator')}
                      className="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                    >
                      <Trash2 className="w-3 h-3" />
                    </button>
                  </div>
                ) : (
                  <button
                    onClick={() => openSignatureModal('coordinator')}
                    className="mx-auto flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                  >
                    <Pen className="w-4 h-4" />
                    Signer
                  </button>
                )}
              </div>
            </div>

            <div className="grid grid-cols-2 gap-8 mt-16">
              <div className="text-center">
                <p className="mb-4">Signature et cachet de L'Etablissement</p>
                {signatures.establishment ? (
                  <div className="relative inline-block">
                    <img src={signatures.establishment} alt="Signature établissement" className="h-20 mx-auto border border-gray-300 rounded" />
                    <button
                      onClick={() => deleteSignature('establishment')}
                      className="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                    >
                      <Trash2 className="w-3 h-3" />
                    </button>
                  </div>
                ) : (
                  <button
                    onClick={() => openSignatureModal('establishment')}
                    className="mx-auto flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                  >
                    <Pen className="w-4 h-4" />
                    Signer
                  </button>
                )}
                <div className="mt-2 text-blue-600 font-bold">SAID RIAN</div>
              </div>
              <div className="text-center">
                <p className="mb-4">Signature et cachet de L'ENTREPRISE</p>
                {signatures.enterprise ? (
                  <div className="relative inline-block">
                    <img src={signatures.enterprise} alt="Signature entreprise" className="h-20 mx-auto border border-gray-300 rounded" />
                    <button
                      onClick={() => deleteSignature('enterprise')}
                      className="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                    >
                      <Trash2 className="w-3 h-3" />
                    </button>
                  </div>
                ) : (
                  <button
                    onClick={() => openSignatureModal('enterprise')}
                    className="mx-auto flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                  >
                    <Pen className="w-4 h-4" />
                    Signer
                  </button>
                )}
              </div>
            </div>

            <p className="text-center mt-8 text-gray-600">2</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default StageConventionTemplate;
