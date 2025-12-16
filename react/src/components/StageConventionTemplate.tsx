import React, { useState, useRef, useEffect } from 'react';
import { FileText, Download, Pen, Trash2, Save, X } from 'lucide-react';

const StageConventionTemplate = () => {
  const [data, setData] = useState({
    studentName: 'SABER MOHAMMED AYMANE',
    filiere: 'Génie Informatique 1ère année',
    dateDebut: '2025-07-01',
    dateFin: '2025-07-31',
    entrepriseNom: 'Procomtech',
    entrepriseAdresse: 'B1 N° 67 Avenue Ghandi, Cité Dakhla, Agadir',
    entrepriseTel: '0662281880',
    entrepriseEmail: 'Procomtech19@gmail.com',
    entrepriseRepresentant: 'AIT IKENE MOHAMED',
    entrepriseQualite: 'GERANT',
    encadrantNom: 'ALOUANI ABDELMAJID',
    tuteurNom: 'ABTOY Anouar',
    themeStage: 'Mise en place d\'un système PDA pour les employés d\'un entreprise agroalimentaire',
    dateSignature: '23-Jun-2025 12:35:57'
  });

  const [signatureEcole, setSignatureEcole] = useState(null);
  const [isDrawing, setIsDrawing] = useState(false);
  const canvasRef = useRef(null);
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

  const startDrawing = (e) => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const rect = canvas.getBoundingClientRect();
    const ctx = canvas.getContext('2d');

    if (ctx) {
      setIsDrawing(true);
      ctx.beginPath();
      ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
    }
  };

  const draw = (e) => {
    if (!isDrawing) return;
    const canvas = canvasRef.current;
    if (!canvas) return;
    const rect = canvas.getBoundingClientRect();
    const ctx = canvas.getContext('2d');

    if (ctx) {
      ctx.strokeStyle = '#000';
      ctx.lineWidth = 2;
      ctx.lineCap = 'round';
      ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
      ctx.stroke();
    }
  };

  const stopDrawing = () => setIsDrawing(false);

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
    if (!canvas) return;
    const signatureData = canvas.toDataURL('image/png');
    setSignatureEcole(signatureData);
    setIsModalOpen(false);
  };

  const deleteSignature = () => {
    setSignatureEcole(null);
  };

  const handleChange = (e) => {
    setData({ ...data, [e.target.name]: e.target.value });
  };

  const generatePDF = () => {
    alert('Générer le PDF avec @react-pdf/renderer incluant la signature digitale de l\'école.');
  };

  return (
    <div className="min-h-screen bg-gray-50 p-4">
      {/* Signature Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
            <div className="flex justify-between items-center mb-4">
              <h3 className="text-xl font-bold">Signature de l'École</h3>
              <button onClick={() => setIsModalOpen(false)} className="text-gray-500 hover:text-gray-700">
                <X className="w-6 h-6" />
              </button>
            </div>
            <div className="border-2 border-gray-300 rounded-lg mb-4 bg-white">
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
      <div className="max-w-5xl mx-auto mb-6 bg-white rounded-lg shadow p-6">
        <h2 className="text-xl font-bold mb-4 flex items-center gap-2">
          <FileText className="w-5 h-5" />
          Données de la Convention
        </h2>
        <div className="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
          <input type="text" name="studentName" value={data.studentName} onChange={handleChange} placeholder="Nom étudiant" className="px-3 py-2 border rounded" />
          <input type="text" name="filiere" value={data.filiere} onChange={handleChange} placeholder="Filière" className="px-3 py-2 border rounded" />
          <input type="date" name="dateDebut" value={data.dateDebut} onChange={handleChange} className="px-3 py-2 border rounded" />
          <input type="date" name="dateFin" value={data.dateFin} onChange={handleChange} className="px-3 py-2 border rounded" />
          <input type="text" name="entrepriseNom" value={data.entrepriseNom} onChange={handleChange} placeholder="Nom entreprise" className="px-3 py-2 border rounded" />
          <input type="text" name="entrepriseTel" value={data.entrepriseTel} onChange={handleChange} placeholder="Tél entreprise" className="px-3 py-2 border rounded" />
          <input type="text" name="entrepriseAdresse" value={data.entrepriseAdresse} onChange={handleChange} placeholder="Adresse" className="col-span-2 px-3 py-2 border rounded" />
          <input type="email" name="entrepriseEmail" value={data.entrepriseEmail} onChange={handleChange} placeholder="Email" className="px-3 py-2 border rounded" />
          <input type="text" name="entrepriseRepresentant" value={data.entrepriseRepresentant} onChange={handleChange} placeholder="Représentant" className="px-3 py-2 border rounded" />
          <input type="text" name="entrepriseQualite" value={data.entrepriseQualite} onChange={handleChange} placeholder="Qualité" className="px-3 py-2 border rounded" />
          <input type="text" name="encadrantNom" value={data.encadrantNom} onChange={handleChange} placeholder="Encadrant" className="px-3 py-2 border rounded" />
          <input type="text" name="tuteurNom" value={data.tuteurNom} onChange={handleChange} placeholder="Tuteur" className="px-3 py-2 border rounded" />
          <input type="text" name="themeStage" value={data.themeStage} onChange={handleChange} placeholder="Thème du stage" className="col-span-3 px-3 py-2 border rounded" />
        </div>
        <button onClick={generatePDF} className="mt-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
          <Download className="w-4 h-4" />
          Générer PDF
        </button>
      </div>

      {/* Document Preview */}
      <div className="max-w-[210mm] mx-auto bg-white shadow-lg" style={{ padding: '20mm 25mm' }}>
        {/* Header */}
        <div className="flex justify-between items-start mb-6 pb-3 border-b-2 border-gray-800">
          <div style={{ fontSize: '10pt', lineHeight: '1.3', fontWeight: 'bold' }}>
            <div>Université Abdelmalek Essaâdi</div>
            <div>Ecole Nationale des Sciences Appliquées</div>
            <div>Tétouan</div>
          </div>
          <div className="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-xs border-2 border-gray-400">
            LOGO
          </div>
          <div style={{ fontSize: '10pt', lineHeight: '1.3', textAlign: 'right', fontWeight: 'bold' }} dir="rtl">
            <div>جامعة عبد المالك السعدي</div>
            <div>المدرسة الوطنية للعلوم التطبيقية</div>
            <div>تطوان</div>
          </div>
        </div>

        <h1 className="text-center font-bold mb-2" style={{ fontSize: '18pt' }}>
          CONVENTION DE STAGE
        </h1>
        <p className="text-center underline italic mb-6" style={{ fontSize: '10pt' }}>
          (2 exemplaires imprimés en recto-verso)
        </p>

        <h2 className="text-center font-bold mb-4" style={{ fontSize: '12pt' }}>ENTRE</h2>

        <div className="mb-4" style={{ fontSize: '10pt', lineHeight: '1.5' }}>
          <p>L'Ecole Nationale des Sciences Appliquées, Université Abdelmalek Essaâdi - Tétouan</p>
          <p>B.P. 2222, Mhannech II, Tétouan, Maroc</p>
          <p>Tél. +212 5 39 68 80 27 ; Fax. +212 39 99 46 24. Web: <span className="text-blue-600 font-bold">https://ensa-tetouan.ac.ma</span></p>
          <p>Représenté par le Professeur <strong>Kamal REKLAOUI</strong> en qualité de Directeur.</p>
          <p className="text-right mt-2">Ci-après, dénommé <strong>l'Etablissement</strong></p>
        </div>

        <h2 className="text-center font-bold mb-4" style={{ fontSize: '12pt' }}>ET</h2>

        <div className="mb-4" style={{ fontSize: '10pt', lineHeight: '1.5' }}>
          <p>La Société : <strong>{data.entrepriseNom}</strong></p>
          <p>Adresse : <strong>{data.entrepriseAdresse}</strong></p>
          <p>Tél : <strong>{data.entrepriseTel}</strong> Email: <strong>{data.entrepriseEmail}</strong></p>
          <p>Représentée par Monsieur <strong>{data.entrepriseRepresentant}</strong> en qualité <strong>{data.entrepriseQualite}</strong></p>
          <p className="text-right mt-2">Ci-après dénommée <strong>L'ENTREPRISE</strong></p>
        </div>

        <div className="mb-4">
          <h3 className="font-bold mb-2" style={{ fontSize: '11pt' }}>Article 1 : Engagement</h3>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify' }}>
            <strong>L'ENTREPRISE</strong> accepte de recevoir à titre de stagiaire <strong>{data.studentName}</strong> étudiant de la filière du Cycle Ingénieur <strong>« {data.filiere} »</strong> de l'ENSA de Tétouan, Université Abdelmalek Essaâdi (Tétouan), pour une période allant du <strong>{data.dateDebut}</strong> au <strong>{data.dateFin}</strong>
          </p>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify', marginTop: '8pt', fontWeight: 'bold' }}>
            En aucun cas, cette convention ne pourra autoriser les étudiants à s'absenter durant la période des contrôles ou des enseignements.
          </p>
        </div>

        <div className="mb-4">
          <h3 className="font-bold mb-2" style={{ fontSize: '11pt' }}>Article 2 : Objet</h3>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify' }}>
            Le stage aura pour objet essentiel d'assurer l'application pratique de l'enseignement donné par <strong>l'Etablissement</strong>, et ce, en organisant des visites sur les installations et en réalisant des études proposées par <strong>L'ENTREPRISE</strong>.
          </p>
        </div>

        <div className="mb-4">
          <h3 className="font-bold mb-2" style={{ fontSize: '11pt' }}>Article 3 : Encadrement et suivi</h3>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify' }}>
            Pour accompagner le Stagiaire durant son stage, et ainsi instaurer une véritable collaboration L'ENTREPRISE/Stagiaire/Etablissement, L'ENTREPRISE désigne Mme/Mr <strong>{data.encadrantNom}</strong> encadrant(e) et parrain(e), pour superviser et assurer la qualité du travail fourni par le Stagiaire.
          </p>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify', marginTop: '8pt' }}>
            L'Etablissement désigne <strong>{data.tuteurNom}</strong> en tant que tuteur qui procurera une assistance pédagogique
          </p>
        </div>

        <div className="mb-4">
          <h3 className="font-bold mb-2" style={{ fontSize: '11pt' }}>Article 4 : Programme:</h3>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify' }}>
            Le thème du stage est: <strong>« {data.themeStage} »</strong>
          </p>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify', marginTop: '8pt' }}>
            Ce programme a été défini conjointement par <strong>l'Etablissement</strong>, <strong>L'ENTREPRISE</strong> et le <strong>Stagiaire</strong>.
          </p>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify', marginTop: '8pt', marginLeft: '20pt' }}>
            Le contenu de ce programme doit permettre au Stagiaire une réflexion en relation avec les enseignements ou le projet de fin d'études qui s'inscrit dans le programme de formation de <strong>l'Etablissement</strong>.
          </p>
        </div>

        <div className="mb-4">
          <h3 className="font-bold mb-2" style={{ fontSize: '11pt' }}>Article 5 : Indemnité de stage</h3>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify' }}>
            Au cours du stage, l'étudiant ne pourra prétendre à aucun salaire de la part de <strong>L'ENTREPRISE</strong>.
          </p>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify', marginTop: '8pt' }}>
            Cependant, si <strong>l'ENTREPRISE</strong> et l'étudiant le conviennent, ce dernier pourra recevoir une indemnité forfaitaire de la part de l'ENTREPRISE des frais occasionnés par la mission confiée à l'étudiant.
          </p>
        </div>

        <div className="mb-4">
          <h3 className="font-bold mb-2" style={{ fontSize: '11pt' }}>Article 6 : Règlement</h3>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify' }}>
            Pendant la durée du stage, le Stagiaire reste placé sous la responsabilité de <strong>l'Etablissement</strong>.
          </p>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify', marginTop: '8pt', fontWeight: 'bold' }}>
            Cependant, l'étudiant est tenu d'informer l'école dans un délai de 24h sur toute modification portant sur la convention déjà signée, sinon il en assumera toute sa responsabilité sur son non-respect de la convention signée par l'école.
          </p>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify', marginTop: '8pt' }}>
            Toutefois, le Stagiaire est soumis à la discipline et au règlement intérieur de <strong>L'ENTREPRISE</strong>.
          </p>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify', marginTop: '8pt' }}>
            En cas de manquement, <strong>L'ENTREPRISE</strong> se réserve le droit de mettre fin au stage après en avoir convenu avec le Directeur de l'Etablissement.
          </p>
        </div>

        <div className="mb-4">
          <h3 className="font-bold mb-2" style={{ fontSize: '11pt' }}>Article 7 : Confidentialité</h3>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify' }}>
            Le Stagiaire et l'ensemble des acteurs liés à son travail (l'administration de <strong>l'Etablissement</strong>, le parrain pédagogique ...) sont tenus au secret professionnel. Ils s'engagent à ne pas diffuser les informations recueillies à des fins de publications, conférences, communications, sans raccord préalable de <strong>L'ENTREPRISE</strong>. Cette obligation demeure valable après l'expiration du stage
          </p>
        </div>

        <div className="mb-4">
          <h3 className="font-bold mb-2" style={{ fontSize: '11pt' }}>Article 8 : Assurance accident de travail</h3>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify' }}>
            <strong>Le stagiaire</strong> devra obligatoirement souscrire une assurance couvrant la Responsabilité Civile et Accident de Travail, durant les stages et trajets effectués.
          </p>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify', marginTop: '8pt' }}>
            En cas d'accident de travail survenant durant la période du stage, <strong>L'ENTREPRISE</strong> s'engage à faire parvenir immédiatement à l'Etablissement toutes les informations indispensables à la déclaration dudit accident.
          </p>
        </div>

        <div className="mb-4">
          <h3 className="font-bold mb-2" style={{ fontSize: '11pt' }}>Article 9: Evaluation de L'ENTREPRISE</h3>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify' }}>
            Le stage accompli, le parrain établira un rapport d'appréciations générales sur le travail effectué et le comportement du Stagiaire durant son séjour chez <strong>L'ENTREPRISE</strong>.
          </p>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify', marginTop: '8pt' }}>
            <strong>L'ENTREPRISE</strong> remettra au Stagiaire une attestation indiquant la nature et la durée des travaux effectués.
          </p>
        </div>

        <div className="mb-6">
          <h3 className="font-bold mb-2" style={{ fontSize: '11pt' }}>Article 10 : Rapport de stage</h3>
          <p style={{ fontSize: '10pt', lineHeight: '1.5', textAlign: 'justify' }}>
            A l'issue de chaque stage, le Stagiaire rédigera un rapport de stage faisant état de ses travaux et de son vécu au sein de <strong>L'ENTREPRISE</strong>. Ce rapport sera communiqué à <strong>L'ENTREPRISE</strong> et restera strictement confidentiel.
          </p>
        </div>

        <p className="text-center mb-8" style={{ fontSize: '10pt', marginLeft: '40pt' }}>
          Fait à Tétouan en deux exemplaires, le <strong>{data.dateSignature}</strong>
        </p>

        {/* Signatures Section */}
        <div className="border-t-2 border-gray-800 pt-6">
          <table className="w-full" style={{ fontSize: '10pt' }}>
            <tbody>
              <tr>
                <td className="w-1/2 text-center align-top pb-20">
                  <div className="font-bold mb-2">Nom et signature du Stagiaire</div>
                </td>
                <td className="w-1/2 text-center align-top pb-20">
                  <div className="font-bold mb-2">Le Coordonnateur de la filière</div>
                </td>
              </tr>
              <tr>
                <td className="w-1/2 text-center align-top">
                  <div className="font-bold mb-4">Signature et cachet de L'Etablissement</div>
                  {signatureEcole ? (
                    <div className="relative inline-block">
                      <img src={signatureEcole} alt="Signature École" className="h-20 mx-auto border border-gray-300 rounded" />
                      <button
                        onClick={deleteSignature}
                        className="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                      >
                        <Trash2 className="w-3 h-3" />
                      </button>
                    </div>
                  ) : (
                    <button
                      onClick={() => setIsModalOpen(true)}
                      className="mx-auto flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                      <Pen className="w-4 h-4" />
                      Signer pour l'École
                    </button>
                  )}
                </td>
                <td className="w-1/2 text-center align-top">
                  <div className="font-bold mb-2">Signature et cachet de L'ENTREPRISE</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default StageConventionTemplate;